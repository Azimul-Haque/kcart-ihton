<?php

return [
  'mode'                  => 'utf-8',
  'format'                => 'A4',
  'author'                => 'A. H. M. Azimul Haque Rifat',
  'subject'               => '',
  'keywords'              => '',
  'creator'               => 'Laravel Pdf',
  'display_mode'          => 'fullpage',
  'tempDir'               => base_path('../temp/'),
  'font_path' => base_path('vendor\mpdf\mpdf\ttfonts'),
  'font_data' => [
    'kalpurush' => [
      'R'  => 'kalpurush.ttf',    // regular font
      'B'  => 'kalpurush.ttf',       // optional: bold font
      'I'  => 'kalpurush.ttf',     // optional: italic font
      'BI' => 'kalpurush.ttf', // optional: bold-italic font
      'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
      //'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
    ],
    'Nikosh' => [
      'R'  => 'Nikosh.ttf',    // regular font
      'B'  => 'Nikosh.ttf',       // optional: bold font
      'I'  => 'Nikosh.ttf',     // optional: italic font
      'BI' => 'Nikosh.ttf', // optional: bold-italic font
      'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
      //'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
    ],
    'Shonar' => [
      'R'  => 'Shonar.ttf',    // regular font
      'B'  => 'Shonarb.ttf',       // optional: bold font
      'I'  => 'Shonar.ttf',     // optional: italic font
      'BI' => 'Shonar.ttf', // optional: bold-italic font
      'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
      //'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
    ],
    'SolaimanLipi' => [
      'R'  => 'SolaimanLipi_20-04-07.ttf',    // regular font
      'B'  => 'SolaimanLipi_20-04-07b.ttf',       // optional: bold font
      'I'  => 'SolaimanLipi_20-04-07.ttf',     // optional: italic font
      'BI' => 'SolaimanLipi_20-04-07.ttf', // optional: bold-italic font
      'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
      //'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
    ],
    'HindSiliguri' => [
      'R'  => 'HindSiliguri-Regular.ttf.ttf',    // regular font
      'B'  => 'HindSiliguri-Regular.ttf',       // optional: bold font
      'I'  => 'HindSiliguri-Regular.ttf',     // optional: italic font
      'BI' => 'HindSiliguri-Regular.ttf', // optional: bold-italic font
      'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
      //'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
    ],
    // ...add as many as you want.
  ]
];

