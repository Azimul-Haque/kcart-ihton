<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Use the facade for DB

class Topic extends Model
{
    public $timestamps = false;
    
    // Ensure this property is cast as an integer if you have one. 
    // Example: protected $casts = ['total_questions_sum' => 'integer'];

    // --- Relationships ---

    public function questions()
    {
        return $this->hasMany('App\Models\Question');
    }

    public function parent()
    {
        // Define relationship with Topic::class, not string, for type hinting
        return $this->belongsTo(Topic::class, 'parent_id');
    }

    public function children()
    {
        // Add the 'with' clause here for deep loading in the API (if desired)
        // return $this->hasMany(Topic::class, 'parent_id')->with('children');
        return $this->hasMany(Topic::class, 'parent_id')->with('children'); 
    }

    // --- Accessors (Full Path) ---

    public function getFullPathAttribute(): string
    {
        // Initialize the path collection with the current topic's name
        $path = collect([$this->name]);
        
        // Ensure the parent is loaded before starting the traversal
        $parent = $this->parent; 

        // Traverse up the hierarchy
        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }

        // Join the path elements with the ' → ' separator
        return $path->join('→');
    }

    // --- NEW: FAST CLIENT-SIDE AGGREGATION HELPERS ---

    /**
     * Recursively collects all descendant IDs using the eagerly loaded 'children' relationship.
     * This is the efficient recursive helper.
     */
    protected function collectDescendantIdsFromEagerLoad(Topic $topic): array
    {
        $ids = [];
        
        // Check if the children relationship is loaded AND has data
        if ($topic->relationLoaded('children')) {
            foreach ($topic->children as $child) {
                // Include the child's ID itself
                $ids[] = $child->id; 
                
                // CRITICAL RECURSION: Recursively call the function for the child
                // This will pull IDs from the grandchild level (Level 3, Level 4, etc.)
                $ids = array_merge($ids, $this->collectDescendantIdsFromEagerLoad($child));
            }
        }
        return $ids;
    }

    // Accessor to generate the 'descendant_ids' array for the Flutter client.
    public function getDescendantIdsAttribute(): array
    {
        // Must ensure 'children' is loaded for this to work.
        if (!$this->relationLoaded('children')) {
            // Fallback: This indicates the API forgot to use ->with('children')
            return [$this->id]; 
        }
        
        // 1. Start with the current topic's ID
        $ids = [$this->id]; 
        
        // 2. Collect all descendant IDs using the loaded relationship data
        $descendants = $this->collectDescendantIdsFromEagerLoad($this);
        
        // 3. Merge and return the unique set
        return array_unique(array_merge($ids, $descendants));
    }
    
    // --- SERVER-SIDE AGGREGATION & DENORMALIZATION ---
    
    /**
     * [OLD/SLOW METHOD] Get the total count of questions for this topic and all its descendants.
     * This method is only useful for initial database population/cleanup now.
     * @return int
     */
    public function getTotalQuestionCountAggregated(): int
    {
        // WARNING: The method definition for getDescendantIds() you provided is highly inefficient
        // for large datasets as it makes multiple DB queries. We rely on the replacement logic below.
        
        // Since we are no longer using this for the API (we use total_questions_sum), 
        // we keep the logic simple for database cleanup purposes.
        $descendantIds = $this->getDescendantIds($this->id); // Using the slow helper
        
        return DB::table('questions')
                 ->whereIn('topic_id', $descendantIds)
                 ->count();
    }
    
    /**
     * [ROLL-UP METHOD] Recalculates and updates the total_questions_sum 
     * for the current topic and recursively rolls up the change to parents.
     */
    public function recalculateAggregatedQuestionCount()
    {
        // 1. Get the local question count (questions directly attached to this topic)
        $localCount = $this->questions()->count();
        
        // 2. Sum the pre-calculated totals of its direct children (EFFICIENT READ)
        $childrenSum = $this->children()->sum('total_questions_sum');
        
        // 3. The new aggregated count for THIS topic.
        $newAggregatedCount = $localCount + $childrenSum;

        // 4. Update the current topic's column if the value has changed.
        if ($this->total_questions_sum !== $newAggregatedCount) {
            $this->total_questions_sum = $newAggregatedCount;
            
            // Use saveQuietly to prevent infinite loop
            $this->saveQuietly();
            
            // 5. Recursively trigger the recalculation for the parent
            if ($this->parent) {
                $this->parent->recalculateAggregatedQuestionCount();
            }
        }
    }
    
    // --- DEPRECATED/INNEFFICIENT METHODS (RETAINED FOR COMPATIBILITY) ---
    
    // NOTE: This original implementation of getDescendantIds() is slow and should be phased out.
    protected function getDescendantIds(int $topicId): array
    {
         // This logic involves multiple DB queries inside a loop/recursion and is highly inefficient.
         // Since it is used by the old getTotalQuestionCountAggregated(), we keep it for now.
         $ids = [$topicId];
         
         // Fetch direct children of the current ID
         $children = $this->children()->where('parent_id', $topicId)->pluck('id')->toArray();
         
         if (empty($children)) {
             return $ids;
         }
         
         $ids = array_merge($ids, $children);
         
         foreach ($children as $childId) {
             $childTopic = self::find($childId);
             if ($childTopic) {
                 $ids = array_merge($ids, $childTopic->getDescendantIds($childId));
             }
         }
         
         return array_unique($ids);
    }
}