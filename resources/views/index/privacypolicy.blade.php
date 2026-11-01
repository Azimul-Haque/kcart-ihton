<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | নথি ট্র্যাকার (Nothi Tracker)</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'Hind Siliguri', 'sans-serif'],
                        bn: ['Hind Siliguri', 'Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', 'Hind Siliguri', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .lang-bn { display: none; }
        html[lang="bn"] .lang-en { display: none; }
        html[lang="bn"] .lang-bn { display: block; }
        html[lang="bn"] inline-bn { display: inline; }
        html[lang="en"] inline-en { display: inline; }

        @media print {
            .no-print { display: none !important; }
            body { background: white; color: black; }
            .print-shadow-none { box-shadow: none !important; border: 1px solid #e2e8f0; }
            .print-p-0 { padding: 0 !important; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-brand-500 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 glass-header border-b border-slate-200/80 no-print transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Logo & Title -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 text-white flex items-center justify-center shadow-md shadow-brand-500/20">
                    <i class="fa-solid fa-folder-tree text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-slate-900 text-lg leading-tight">
                        <span class="lang-en">Nothi Tracker</span>
                        <span class="lang-bn">নথি ট্র্যাকার</span>
                    </h1>
                    <p class="text-xs text-slate-500 font-medium">
                        <span class="lang-en">Developed by App Lab IT</span>
                        <span class="lang-bn">অ্যাপ ল্যাব আইটি দ্বারা বিকশিত</span>
                    </p>
                </div>
            </div>

            <!-- Controls (Search, Language & Print) -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                
                <!-- Quick Search -->
                <div class="relative hidden md:block">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" onkeyup="searchPolicy()" 
                        placeholder="Search policy..." 
                        class="pl-9 pr-4 py-1.5 text-sm rounded-full bg-slate-100 border-none focus:ring-2 focus:ring-brand-500 w-48 transition-all focus:w-64 text-slate-800">
                </div>

                <!-- Language Toggle -->
                <button onclick="toggleLanguage()" id="langToggleBtn" 
                    class="flex items-center space-x-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold px-3 py-1.5 rounded-full text-xs transition-all border border-slate-200 shadow-sm"
                    title="Switch Language">
                    <i class="fa-solid fa-language text-brand-600 text-base"></i>
                    <span id="langBtnText">বাংলায় দেখুন</span>
                </button>

                <!-- Print Button -->
                <button onclick="window.print()" 
                    class="flex items-center space-x-1.5 bg-brand-600 hover:bg-brand-700 text-white font-medium px-3.5 py-1.5 rounded-full text-xs transition-all shadow-sm shadow-brand-600/30">
                    <i class="fa-solid fa-print"></i>
                    <span class="hidden sm:inline">
                        <span class="lang-en">Print / PDF</span>
                        <span class="lang-bn">প্রিন্ট / পিডিএফে সেভ</span>
                    </span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">
        
        <!-- Hero Header Card -->
        <div class="bg-gradient-to-r from-slate-900 via-brand-950 to-slate-900 rounded-3xl p-6 sm:p-10 text-white mb-8 shadow-xl relative overflow-hidden print-shadow-none">
            <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 max-w-3xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-brand-500/20 text-brand-300 border border-brand-500/30 mb-4">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span class="lang-en">Official Privacy Documentation</span>
                    <span class="lang-bn">অফিসিয়াল গোপনীয়তা সুরক্ষা নীতিমালা</span>
                </span>
                
                <h2 class="text-2xl sm:text-4xl font-bold tracking-tight mb-3">
                    <span class="lang-en">Privacy Policy for Nothi Tracker</span>
                    <span class="lang-bn">নথি ট্র্যাকার অ্যাপের গোপনীয়তা নীতি</span>
                </h2>
                
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    <span class="lang-en">This Privacy Policy governs the collection, usage, and protection of data within the <strong>Nothi Tracker</strong> mobile application developed for internal land office management and document workflow systems.</span>
                    <span class="lang-bn">এই গোপনীয়তা নীতিটি ভূমি অফিস ব্যবস্থাপনা এবং নথি ট্র্যাকিং সিস্টেমে <strong>নথি ট্র্যাকার</strong> মোবাইল অ্যাপ্লিকেশন দ্বারা সংগৃহীত ডাটা ও তার সুরক্ষা নিশ্চিতকরণ সম্পর্কিত নিয়মাবলী ব্যাখ্যা করে।</span>
                </p>

                <div class="mt-6 flex flex-wrap gap-4 text-xs text-slate-400 border-t border-slate-800 pt-4">
                    <div><i class="fa-solid fa-building text-brand-400 mr-1.5"></i><strong>Developer:</strong> App Lab IT</div>
                    <div><i class="fa-solid fa-calendar-check text-brand-400 mr-1.5"></i><strong>Effective Date:</strong> November 2026</div>
                    <div><i class="fa-solid fa-arrows-rotate text-brand-400 mr-1.5"></i><strong>Last Updated:</strong> November 02, 2026</div>
                </div>
            </div>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Navigation Sidebar (Sticky) -->
            <aside class="lg:col-span-1 no-print">
                <div class="sticky top-24 space-y-2 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">
                        <span class="lang-en">On This Page</span>
                        <span class="lang-bn">সূচিপত্র</span>
                    </h3>
                    <nav class="space-y-1 text-sm font-medium">
                        <a href="#intro" class="flex items-center px-3 py-2 text-slate-700 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">
                            <i class="fa-solid fa-circle-info w-6 text-brand-600"></i>
                            <span class="lang-en">1. Introduction</span>
                            <span class="lang-bn">১. ভূমিকা</span>
                        </a>
                        <a href="#data-collected" class="flex items-center px-3 py-2 text-slate-700 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">
                            <i class="fa-solid fa-database w-6 text-brand-600"></i>
                            <span class="lang-en">2. Data Collected</span>
                            <span class="lang-bn">২. সংগৃহীত তথ্য</span>
                        </a>
                        <a href="#permissions" class="flex items-center px-3 py-2 text-slate-700 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">
                            <i class="fa-solid fa-key w-6 text-brand-600"></i>
                            <span class="lang-en">3. App Permissions</span>
                            <span class="lang-bn">৩. ডিভাইস পারমিশন</span>
                        </a>
                        <a href="#data-usage" class="flex items-center px-3 py-2 text-slate-700 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">
                            <i class="fa-solid fa-lock w-6 text-brand-600"></i>
                            <span class="lang-en">4. Security & Storage</span>
                            <span class="lang-bn">৪. ডেটা নিরাপত্তা</span>
                        </a>
                        <a href="#third-party" class="flex items-center px-3 py-2 text-slate-700 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">
                            <i class="fa-solid fa-user-shield w-6 text-brand-600"></i>
                            <span class="lang-en">5. Third-Party Services</span>
                            <span class="lang-bn">৫. থার্ড-পার্টি সার্ভিস</span>
                        </a>
                        <a href="#contact" class="flex items-center px-3 py-2 text-slate-700 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">
                            <i class="fa-solid fa-envelope w-6 text-brand-600"></i>
                            <span class="lang-en">6. Contact Us</span>
                            <span class="lang-bn">৬. যোগাযোগ</span>
                        </a>
                    </nav>

                    <div class="pt-4 border-t border-slate-100 px-3">
                        <a href="https://nothi.orbachinujbuk.com" target="_blank" class="w-full inline-flex items-center justify-center space-x-2 text-xs font-semibold bg-slate-900 text-white py-2.5 rounded-xl hover:bg-brand-600 transition-all">
                            <span>nothi.orbachinujbuk.com</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Policy Content Sections -->
            <div class="lg:col-span-3 space-y-6" id="policyContent">
                
                <!-- Section 1: Introduction -->
                <section id="intro" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm print-shadow-none">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm">1</div>
                        <h3 class="text-xl font-bold text-slate-900">
                            <span class="lang-en">Introduction & Scope</span>
                            <span class="lang-bn">ভূমিকা ও প্রয়োগক্ষেত্র</span>
                        </h3>
                    </div>
                    <div class="text-slate-600 leading-relaxed space-y-3 text-sm sm:text-base">
                        <p class="lang-en">
                            Welcome to <strong>নথি ট্র্যাকার (Nothi Tracker)</strong>. This mobile application is developed by <strong>App Lab IT</strong> for managing physical document workflows, tracking file desks, and generating thermal hearing slips specifically tailored for government office operations (such as Upazila Land Office Bhairab, Kishoreganj).
                        </p>
                        <p class="lang-en">
                            We respect your privacy and are committed to protecting the administrative data processed through our software. This policy outlines how information is handled within the app and system backend.
                        </p>
                        
                        <p class="lang-bn">
                            <strong>নথি ট্র্যাকার (Nothi Tracker)</strong> অ্যাপ্লিকেশনে আপনাকে স্বাগতম। এই মোবাইল অ্যাপ্লিকেশনটি <strong>অ্যাপ ল্যাব আইটি (App Lab IT)</strong> দ্বারা সরকারি দপ্তর এবং উপজেলা ভূমি অফিস, ভৈরব, কিশোরগঞ্জ-এর ফাইল ট্র্যাকিং, কিউআর কোড স্ক্যানিং এবং কার্যতালিকা ব্যবস্থাপনার জন্য নির্মিত হয়েছে।
                        </p>
                        <p class="lang-bn">
                            আমরা আপনার তথ্যের সম্পূর্ণ নিরাপত্তা ও গোপনীয়তা বজায় রাখতে প্রতিশ্রুতিবদ্ধ। এই নীতিমালার মাধ্যমে অ্যাপটি ব্যবহারের সময় কীভাবে তথ্য সংরক্ষণ ও ব্যবহার করা হয় তা স্পষ্টভাবে ব্যাখ্যা করা হয়েছে।
                        </p>
                    </div>
                </section>

                <!-- Section 2: Data Collected -->
                <section id="data-collected" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm print-shadow-none">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm">2</div>
                        <h3 class="text-xl font-bold text-slate-900">
                            <span class="lang-en">Information We Collect</span>
                            <span class="lang-bn">সংগৃহীত তথ্যসমূহ</span>
                        </h3>
                    </div>
                    
                    <div class="text-slate-600 space-y-4 text-sm sm:text-base">
                        <p class="lang-en">To ensure real-time status updates and authorized file operations across desks, Nothi Tracker collects minimal required information:</p>
                        <p class="lang-bn">সিস্টেমের রিয়েল-টাইম ফাইল আদান-প্রদান ও অনুমোদিত ব্যবহার নিশ্চিত করতে আমরা কেবল আবশ্যকীয় তথ্য সংগ্রহ করি:</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                                <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
                                    <i class="fa-solid fa-id-card text-brand-600"></i>
                                    <span class="lang-en">Official User Credentials</span>
                                    <span class="lang-bn">ব্যবহারকারী প্রোফাইল তথ্য</span>
                                </h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    <span class="lang-en">Full Name, Designation, and Registered Mobile Number for authorized staff login and activity logging.</span>
                                    <span class="lang-bn">অনুমোদিত স্টাফদের লগইন নিশ্চিত করতে পূর্ণ নাম, পদবি এবং রেজিস্টার্ড মোবাইল নম্বর সংগ্রহ করা হয়।</span>
                                </p>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                                <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
                                    <i class="fa-solid fa-file-signature text-brand-600"></i>
                                    <span class="lang-en">Case & Document Metadata</span>
                                    <span class="lang-bn">মামলা ও নথি সংক্রান্ত তথ্য</span>
                                </h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    <span class="lang-en">Case Numbers, Plaintiff/Defendant names, contact numbers, hearing dates, and transit location IDs.</span>
                                    <span class="lang-bn">মামলা নম্বর, বাদী-বিবাদীর নাম, মোবাইল নম্বর, পরবর্তী শুনানির তারিখ ও বর্তমান নথি অবস্থান।</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 3: App Permissions -->
                <section id="permissions" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm print-shadow-none">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm">3</div>
                        <h3 class="text-xl font-bold text-slate-900">
                            <span class="lang-en">Device Permissions Required</span>
                            <span class="lang-bn">ডিভাইস পারমিশনসমূহ</span>
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 p-3.5 rounded-xl border border-slate-100 hover:border-brand-200 transition-all">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-camera text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                                    <span class="lang-en">Camera Permission (`CAMERA`)</span>
                                    <span class="lang-bn">ক্যামেরা অ্যাক্সেস (`CAMERA`)</span>
                                </h4>
                                <p class="text-xs sm:text-sm text-slate-600 mt-1">
                                    <span class="lang-en">Strictly required for scanning 2D/QR codes printed on physical file covers and thermal slips to quickly receive and forward files. Camera data is processed locally and never recorded or transmitted.</span>
                                    <span class="lang-bn">নথির কাভার এবং স্লিপে থাকা কিউআর কোড (QR Code) স্ক্যান করে দ্রুত ফাইল রিসিভ ও ফরোয়ার্ড করার জন্য ব্যবহার করা হয়। ক্যামেরা কেবল কোড স্ক্যানিংয়ের জন্য কাজ করে এবং কোনো ভিডিও সঞ্চয় করে না।</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3.5 rounded-xl border border-slate-100 hover:border-brand-200 transition-all">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-wifi text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                                    <span class="lang-en">Internet Access (`INTERNET`)</span>
                                    <span class="lang-bn">ইন্টারনেট পারমিশন (`INTERNET`)</span>
                                </h4>
                                <p class="text-xs sm:text-sm text-slate-600 mt-1">
                                    <span class="lang-en">Required to synchronize transit lists, desk counters, and file status updates in real-time via secured REST API backends.</span>
                                    <span class="lang-bn">ড্যাশবোর্ড কাউন্টার এবং ফাইল স্থানান্তরের তথ্য নিরাপদে সার্ভারের সাথে রিয়েল-টাইমে সিঙ্ক করার জন্য ইন্টারনেট পারমিশন প্রয়োজন হয়।</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 4: Data Security -->
                <section id="data-usage" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm print-shadow-none">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm">4</div>
                        <h3 class="text-xl font-bold text-slate-900">
                            <span class="lang-en">Data Security & Storage</span>
                            <span class="lang-bn">ডেটা সুরক্ষা ও নিরাপত্তা</span>
                        </h3>
                    </div>

                    <div class="text-slate-600 space-y-3 text-sm sm:text-base leading-relaxed">
                        <p class="lang-en">
                            We employ robust administrative and technological security measures to guard official records:
                        </p>
                        <p class="lang-bn">
                            অফিসিয়াল তথ্যের সর্বোচ্চ নিরাপত্তা বজায় রাখতে আমরা আধুনিক প্রযুক্তিগত ব্যবস্থা গ্রহণ করে থাকি:
                        </p>

                        <ul class="space-y-2 text-xs sm:text-sm font-medium text-slate-700 pl-4 border-l-2 border-brand-500">
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-brand-600"></i>
                                <span class="lang-en"><strong>Encrypted Communication:</strong> All REST API payload transmissions use SSL/TLS encryption.</span>
                                <span class="lang-bn"><strong>এনক্রিপ্টেড ডাটা ট্রান্সফার:</strong> সমস্ত ডাটা SSL/TLS এনক্রিপশনের মাধ্যমে সার্ভারে প্রেরিত হয়।</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-brand-600"></i>
                                <span class="lang-en"><strong>Token Authentication:</strong> User sessions are managed safely using Sanctum Bearer Tokens.</span>
                                <span class="lang-bn"><strong>টোকেন অথেনটিকেশন:</strong> সুরক্ষিত স্যাঙ্কটম বিয়ারার টোকেন দ্বারা সেশন নিয়ন্ত্রণ করা হয়।</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-brand-600"></i>
                                <span class="lang-en"><strong>Archive Exclusion:</strong> Completed or disposed files (`নিষ্পত্তি`) are auto-excluded from public listing.</span>
                                <span class="lang-bn"><strong>অার্কাইভ ফিল্টারিং:</strong> নিষ্পত্তি হওয়া নথি সাধারণ তালিকা থেকে সুরক্ষিতভাবে সরিয়ে রাখা হয়।</span>
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- Section 5: Third-Party Services -->
                <section id="third-party" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm print-shadow-none">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm">5</div>
                        <h3 class="text-xl font-bold text-slate-900">
                            <span class="lang-en">Third-Party Disclosure</span>
                            <span class="lang-bn">তৃতীয় পক্ষ সংক্রান্ত ঘোষণা</span>
                        </h3>
                    </div>

                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                        <span class="lang-en"><strong>No Commercial Third-Party Ad SDKs:</strong> Nothi Tracker contains <strong>NO advertisements</strong>, tracking analytics, or commercial third-party profiling libraries. Your usage data is strictly restricted to organizational administration.</span>
                        <span class="lang-bn"><strong>কোনো থার্ড-পার্টি বিজ্ঞাপন বা ট্র্যাকিং নেই:</strong> এই অ্যাপটিতে <strong>কোনো বাণিজ্যিক বিজ্ঞাপন বা ট্র্যাকিং এসডিকে নেই</strong>। প্রশাসনিক নিরাপত্তার স্বার্থে ব্যবহারকারীর কোনো তথ্য বাণিজ্যিক উদ্দেশ্যে কারো কাছে বিক্রি বা শেয়ার করা হয় না।</span>
                    </p>
                </section>

                <!-- Section 6: Contact Information -->
                <section id="contact" class="bg-gradient-to-br from-brand-900 to-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-lg print-shadow-none">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-brand-500 text-white flex items-center justify-center font-bold text-sm">6</div>
                        <h3 class="text-xl font-bold">
                            <span class="lang-en">Contact Developer & Support</span>
                            <span class="lang-bn">যোগাযোগ ও সহায়তা</span>
                        </h3>
                    </div>

                    <p class="text-slate-300 text-sm mb-6">
                        <span class="lang-en">For technical support, feature requests, or queries regarding this privacy statement, please contact the developer:</span>
                        <span class="lang-bn">কারিগরি সহায়তা বা এই নীতিমালার বিষয়ে যেকোনো প্রশ্নের জন্য সরাসরি ডেভলপার টিমের সাথে যোগাযোগ করুন:</span>
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/10">
                            <div class="text-brand-400 text-xs font-semibold mb-1">Company Name</div>
                            <div class="font-bold text-sm">App Lab IT</div>
                        </div>

                        <a href="mailto:bsse0519@iit.du.ac.bd" class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/10 hover:bg-white/20 transition-all group">
                            <div class="text-brand-400 text-xs font-semibold mb-1">Official Email</div>
                            <div class="font-bold text-sm group-hover:text-brand-300 truncate">bsse0519@iit.du.ac.bd</div>
                        </a>

                        <a href="https://nothi.orbachinujbuk.com" target="_blank" class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/10 hover:bg-white/20 transition-all group">
                            <div class="text-brand-400 text-xs font-semibold mb-1">Portal Website</div>
                            <div class="font-bold text-sm group-hover:text-brand-300 truncate">nothi.orbachinujbuk.com</div>
                        </a>
                    </div>
                </section>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-6 no-print">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            <p>© 2026 <strong>App Lab IT</strong>. All Rights Reserved. | <strong>নথি ট্র্যাকার (Nothi Tracker)</strong></p>
        </div>
    </footer>

    <!-- JavaScript Logic for Language Toggle & Search -->
    <script>
        let currentLang = 'en';

        function toggleLanguage() {
            const htmlTag = document.documentElement;
            const btnText = document.getElementById('langBtnText');
            
            if (currentLang === 'en') {
                htmlTag.setAttribute('lang', 'bn');
                currentLang = 'bn';
                btnText.textContent = 'Switch to English';
            } else {
                htmlTag.setAttribute('lang', 'en');
                currentLang = 'en';
                btnText.textContent = 'বাংলায় দেখুন';
            }
        }

        function searchPolicy() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const sections = document.querySelectorAll('#policyContent > section');

            sections.forEach(section => {
                const text = section.innerText.toLowerCase();
                if (text.includes(input)) {
                    section.style.display = "block";
                } else {
                    section.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>