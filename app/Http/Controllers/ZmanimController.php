<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ZmanimController extends Controller
{
    private $hebrewNames = [
        'chatzotNight' => 'חצות הלילה',
        'alotHaShachar' => 'עלות השחר',
        'misheyakir' => 'משיכיר',
        'misheyakirMachmir' => 'משיכיר לחומרה',
        'dawn' => 'עמוד השחר',
        'sunrise' => 'הנץ החמה',
        'sofZmanShmaMGA19Point8' => 'סוף זמן ק״ש מג״א (19.8°)',
        'sofZmanShmaMGA16Point1' => 'סוף זמן ק״ש מג״א (16.1°)',
        'sofZmanShmaMGA' => 'סוף זמן ק״ש מג״א (72 דקות)',
        'sofZmanShma' => 'סוף זמן ק״ש גר״א',
        'sofZmanTfillaMGA19Point8' => 'סוף זמן תפילה מג״א (19.8°)',
        'sofZmanTfillaMGA16Point1' => 'סוף זמן תפילה מג״א (16.1°)',
        'sofZmanTfillaMGA' => 'סוף זמן תפילה מג״א (72 דקות)',
        'sofZmanTfilla' => 'סוף זמן תפילה גר״א',
        'chatzot' => 'חצות',
        'minchaGedola' => 'מנחה גדולה',
        'minchaGedolaMGA' => 'מנחה גדולה מג״א',
        'minchaKetana' => 'מנחה קטנה',
        'minchaKetanaMGA' => 'מנחה קטנה מג״א',
        'plagHaMincha' => 'פלג המנחה',
        'sunset' => 'שקיעה',
        'beinHaShmashos' => 'בין השמשות',
        'dusk' => 'סוף השקיעה',
        'tzeit7083deg' => 'צאת הכוכבים (7.083°)',
        'tzeit85deg' => 'צאת הכוכבים (8.5°)',
        'tzeit42min' => 'צאת הכוכבים (42 דקות)',
        'tzeit50min' => 'צאת הכוכבים (50 דקות)',
        'tzeit72min' => 'צאת הכוכבים (72 דקות)',
    ];

    private $translations = [
        'en' => [
            'chatzotNight' => 'Midnight',
            'alotHaShachar' => 'Dawn',
            'misheyakir' => 'Earliest Tallit',
            'misheyakirMachmir' => 'Earliest Tallit (Stringent)',
            'dawn' => 'Civil Dawn',
            'sunrise' => 'Sunrise',
            'sofZmanShmaMGA19Point8' => 'Latest Shema MGA (19.8°)',
            'sofZmanShmaMGA16Point1' => 'Latest Shema MGA (16.1°)',
            'sofZmanShmaMGA' => 'Latest Shema MGA (72 min)',
            'sofZmanShma' => 'Latest Shema GRA',
            'sofZmanTfillaMGA19Point8' => 'Latest Tefillah MGA (19.8°)',
            'sofZmanTfillaMGA16Point1' => 'Latest Tefillah MGA (16.1°)',
            'sofZmanTfillaMGA' => 'Latest Tefillah MGA (72 min)',
            'sofZmanTfilla' => 'Latest Tefillah GRA',
            'chatzot' => 'Midday',
            'minchaGedola' => 'Earliest Mincha GRA',
            'minchaGedolaMGA' => 'Earliest Mincha MGA',
            'minchaKetana' => 'Mincha Ketana GRA',
            'minchaKetanaMGA' => 'Mincha Ketana MGA',
            'plagHaMincha' => 'Plag HaMincha',
            'sunset' => 'Sunset',
            'beinHaShmashos' => 'Twilight',
            'dusk' => 'Civil Dusk',
            'tzeit7083deg' => 'Nightfall (7.083°)',
            'tzeit85deg' => 'Nightfall (8.5°)',
            'tzeit42min' => 'Nightfall (42 min)',
            'tzeit50min' => 'Nightfall (50 min)',
            'tzeit72min' => 'Nightfall (72 min)',
        ],
        'es' => [
            'chatzotNight' => 'Medianoche',
            'alotHaShachar' => 'Amanecer',
            'misheyakir' => 'Talit más temprano',
            'misheyakirMachmir' => 'Talit más temprano (Estricto)',
            'dawn' => 'Alba civil',
            'sunrise' => 'Salida del sol',
            'sofZmanShmaMGA19Point8' => 'Último Shemá MGA (19.8°)',
            'sofZmanShmaMGA16Point1' => 'Último Shemá MGA (16.1°)',
            'sofZmanShmaMGA' => 'Último Shemá MGA (72 min)',
            'sofZmanShma' => 'Último Shemá GRA',
            'sofZmanTfillaMGA19Point8' => 'Última Tefilá MGA (19.8°)',
            'sofZmanTfillaMGA16Point1' => 'Última Tefilá MGA (16.1°)',
            'sofZmanTfillaMGA' => 'Última Tefilá MGA (72 min)',
            'sofZmanTfilla' => 'Última Tefilá GRA',
            'chatzot' => 'Mediodía',
            'minchaGedola' => 'Minjá temprana GRA',
            'minchaGedolaMGA' => 'Minjá temprana MGA',
            'minchaKetana' => 'Minjá Ketaná GRA',
            'minchaKetanaMGA' => 'Minjá Ketaná MGA',
            'plagHaMincha' => 'Plag HaMinjá',
            'sunset' => 'Puesta del sol',
            'beinHaShmashos' => 'Crepúsculo',
            'dusk' => 'Anochecer civil',
            'tzeit7083deg' => 'Anochecer (7.083°)',
            'tzeit85deg' => 'Anochecer (8.5°)',
            'tzeit42min' => 'Anochecer (42 min)',
            'tzeit50min' => 'Anochecer (50 min)',
            'tzeit72min' => 'Anochecer (72 min)',
        ],
        'ar' => [
            'chatzotNight' => 'منتصف الليل',
            'alotHaShachar' => 'الفجر',
            'misheyakir' => 'أقرب وقت للطاليت',
            'misheyakirMachmir' => 'أقرب وقت للطاليت (صارم)',
            'dawn' => 'الفجر المدني',
            'sunrise' => 'شروق الشمس',
            'sofZmanShmaMGA19Point8' => 'آخر وقت شيما MGA (19.8°)',
            'sofZmanShmaMGA16Point1' => 'آخر وقت شيما MGA (16.1°)',
            'sofZmanShmaMGA' => 'آخر وقت شيما MGA (72 دقيقة)',
            'sofZmanShma' => 'آخر وقت شيما GRA',
            'sofZmanTfillaMGA19Point8' => 'آخر وقت الصلاة MGA (19.8°)',
            'sofZmanTfillaMGA16Point1' => 'آخر وقت الصلاة MGA (16.1°)',
            'sofZmanTfillaMGA' => 'آخر وقت الصلاة MGA (72 دقيقة)',
            'sofZmanTfilla' => 'آخر وقت الصلاة GRA',
            'chatzot' => 'منتصف النهار',
            'minchaGedola' => 'مينحا المبكرة GRA',
            'minchaGedolaMGA' => 'مينحا المبكرة MGA',
            'minchaKetana' => 'مينحا كيتانا GRA',
            'minchaKetanaMGA' => 'مينحا كيتانا MGA',
            'plagHaMincha' => 'بلاغ هامينحا',
            'sunset' => 'غروب الشمس',
            'beinHaShmashos' => 'الشفق',
            'dusk' => 'الغسق المدني',
            'tzeit7083deg' => 'حلول الليل (7.083°)',
            'tzeit85deg' => 'حلول الليل (8.5°)',
            'tzeit42min' => 'حلول الليل (42 دقيقة)',
            'tzeit50min' => 'حلول الليل (50 دقيقة)',
            'tzeit72min' => 'حلول الليل (72 دقيقة)',
        ],
    ];

    public function getZmanim(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'date' => 'nullable|date',
            'timezone' => 'nullable|string',
            'lang' => 'nullable|string|in:en,es,he,ar',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $date = $request->input('date', now()->format('Y-m-d'));
        $lang = $request->input('lang', 'en');

        // Call Hebcal API
        $response = Http::get('https://www.hebcal.com/zmanim', [
            'cfg' => 'json',
            'latitude' => $latitude,
            'longitude' => $longitude,
            'date' => $date,
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch Zmanim'], 500);
        }

        $data = $response->json();
        $times = $data['times'] ?? [];
        $timezone = $data['location']['tzid'] ?? 'UTC';

        $zmanim = [];
        $sortedTimes = [];

        // Process times from Hebcal API
        foreach ($times as $key => $timeString) {
            // Parse the time and format it as HH:MM
            $carbonTime = Carbon::parse($timeString);
            $formattedTime = $carbonTime->format('H:i');
            
            // Get Hebrew name
            $hebrewName = $this->hebrewNames[$key] ?? $key;
            
            // Get translation based on language
            $translation = null;
            if ($lang !== 'he' && isset($this->translations[$lang][$key])) {
                $translation = $this->translations[$lang][$key];
            }
            
            $zmanData = [
                'key' => $key,
                'name' => $hebrewName,
                'translation' => $translation,
                'time' => $formattedTime,
            ];
            
            // Use the time as key for sorting
            $sortedTimes[$formattedTime . '_' . $key] = $zmanData;
        }

        // Sort by time
        ksort($sortedTimes);
        
        // Convert to indexed array
        $zmanim = array_values($sortedTimes);

        return response()->json([
            'zmanim' => $zmanim,
            'location' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'timezone' => $timezone,
            ],
            'date' => $date,
        ]);
    }

    public function getLocations(Request $request)
    {
        $lang = $request->lang ?? 'en';
        $search = strtolower($request->search ?? '');
        
        $locations = [
            [
                'id' => 1,
                'name' => $this->getLocationName('Jerusalem', $lang),
                'latitude' => 31.7683,
                'longitude' => 35.2137,
                'timezone' => 'Asia/Jerusalem'
            ],
            [
                'id' => 2,
                'name' => $this->getLocationName('New York', $lang),
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'timezone' => 'America/New_York'
            ],
            [
                'id' => 3,
                'name' => $this->getLocationName('London', $lang),
                'latitude' => 51.5074,
                'longitude' => -0.1278,
                'timezone' => 'Europe/London'
            ],
            [
                'id' => 4,
                'name' => $this->getLocationName('Tel Aviv', $lang),
                'latitude' => 32.0853,
                'longitude' => 34.7818,
                'timezone' => 'Asia/Jerusalem'
            ],
            [
                'id' => 5,
                'name' => $this->getLocationName('Los Angeles', $lang),
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'timezone' => 'America/Los_Angeles'
            ],
            [
                'id' => 6,
                'name' => $this->getLocationName('Miami', $lang),
                'latitude' => 25.7617,
                'longitude' => -80.1918,
                'timezone' => 'America/New_York'
            ],
            [
                'id' => 7,
                'name' => $this->getLocationName('Paris', $lang),
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'timezone' => 'Europe/Paris'
            ],
            [
                'id' => 8,
                'name' => $this->getLocationName('Montreal', $lang),
                'latitude' => 45.5017,
                'longitude' => -73.5673,
                'timezone' => 'America/Montreal'
            ],
            [
                'id' => 9,
                'name' => $this->getLocationName('Mexico City', $lang),
                'latitude' => 19.4326,
                'longitude' => -99.1332,
                'timezone' => 'America/Mexico_City'
            ],
            [
                'id' => 10,
                'name' => $this->getLocationName('Buenos Aires', $lang),
                'latitude' => -34.6037,
                'longitude' => -58.3816,
                'timezone' => 'America/Argentina/Buenos_Aires'
            ]
        ];
        
        // Filter locations by search term if provided
        if (!empty($search)) {
            $locations = array_filter($locations, function($location) use ($search) {
                return stripos($location['name'], $search) !== false;
            });
            $locations = array_values($locations); // Re-index array
        }
        
        return response()->json([
            'status' => 'success',
            'locations' => $locations
        ]);
    }

    private function getLocationName($city, $lang)
    {
        $names = [
            'Jerusalem' => [
                'en' => 'Jerusalem',
                'es' => 'Jerusalén',
                'he' => 'ירושלים',
                'ar' => 'القدس'
            ],
            'New York' => [
                'en' => 'New York',
                'es' => 'Nueva York',
                'he' => 'ניו יורק',
                'ar' => 'نيويورك'
            ],
            'London' => [
                'en' => 'London',
                'es' => 'Londres',
                'he' => 'לונדון',
                'ar' => 'لندن'
            ],
            'Tel Aviv' => [
                'en' => 'Tel Aviv',
                'es' => 'Tel Aviv',
                'he' => 'תל אביב',
                'ar' => 'تل أبيب'
            ],
            'Los Angeles' => [
                'en' => 'Los Angeles',
                'es' => 'Los Ángeles',
                'he' => 'לוס אנג\'לס',
                'ar' => 'لوس أنجلوس'
            ],
            'Miami' => [
                'en' => 'Miami',
                'es' => 'Miami',
                'he' => 'מיאמי',
                'ar' => 'ميامي'
            ],
            'Paris' => [
                'en' => 'Paris',
                'es' => 'París',
                'he' => 'פריז',
                'ar' => 'باريس'
            ],
            'Montreal' => [
                'en' => 'Montreal',
                'es' => 'Montreal',
                'he' => 'מונטריאול',
                'ar' => 'مونتريال'
            ],
            'Mexico City' => [
                'en' => 'Mexico City',
                'es' => 'Ciudad de México',
                'he' => 'מקסיקו סיטי',
                'ar' => 'مكسيكو سيتي'
            ],
            'Buenos Aires' => [
                'en' => 'Buenos Aires',
                'es' => 'Buenos Aires',
                'he' => 'בואנוס איירס',
                'ar' => 'بوينس آيرس'
            ]
        ];
        
        return $names[$city][$lang] ?? $city;
    }
}