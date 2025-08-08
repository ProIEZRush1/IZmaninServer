<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ZmanimController extends Controller
{
    private $hebrewNames = [
        'alot_hashachar' => 'עלות השחר',
        'misheyakir' => 'משיכיר',
        'sunrise' => 'הנץ החמה',
        'sof_zman_shema' => 'סוף זמן קריאת שמע',
        'sof_zman_tefillah' => 'סוף זמן תפילה',
        'chatzot' => 'חצות',
        'mincha_gedolah' => 'מנחה גדולה',
        'mincha_ketanah' => 'מנחה קטנה',
        'plag_hamincha' => 'פלג המנחה',
        'sunset' => 'שקיעה',
        'tzeis_hakochavim' => 'צאת הכוכבים',
        'chatzot_layla' => 'חצות לילה'
    ];

    private $translations = [
        'en' => [
            'sunrise' => 'Sunrise',
            'sunset' => 'Sunset'
        ],
        'es' => [
            'sunrise' => 'Salida del Sol',
            'sunset' => 'Puesta del Sol'
        ],
        'he' => [
            'sunrise' => 'הנץ החמה',
            'sunset' => 'שקיעה'
        ],
        'ar' => [
            'sunrise' => 'شروق الشمس',
            'sunset' => 'غروب الشمس'
        ]
    ];

    public function getZmanim(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'date' => 'nullable|date',
            'timezone' => 'nullable|string',
            'lang' => 'nullable|in:en,es,he,ar'
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $date = $request->date ?? Carbon::now()->format('Y-m-d');
        $timezone = $request->timezone ?? 'UTC';
        $lang = $request->lang ?? 'en';

        try {
            $zmanim = $this->calculateZmanim($latitude, $longitude, $date, $timezone);
            $translatedZmanim = $this->translateZmanim($zmanim, $lang);
            
            return response()->json([
                'status' => 'success',
                'date' => $date,
                'location' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'timezone' => $timezone
                ],
                'zmanim' => $translatedZmanim,
                'language' => $lang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateZmanim($latitude, $longitude, $date, $timezone)
    {
        // For now, using local calculation
        // In production, you can integrate with a real Zmanim API
        return $this->calculateLocalZmanim($latitude, $longitude, $date, $timezone);
    }

    private function calculateLocalZmanim($latitude, $longitude, $date, $timezone)
    {
        // Basic astronomical calculations for zmanim
        $dateTime = Carbon::parse($date, $timezone);
        
        // These are simplified calculations - in production, use proper astronomical formulas
        $sunrise = $dateTime->copy()->setTime(6, 0);
        $sunset = $dateTime->copy()->setTime(18, 30);
        $dayLength = $sunset->diffInMinutes($sunrise);
        $shaahZmanit = $dayLength / 12;
        
        return [
            'alot_hashachar' => $sunrise->copy()->subMinutes(72)->format('H:i'),
            'misheyakir' => $sunrise->copy()->subMinutes(45)->format('H:i'),
            'sunrise' => $sunrise->format('H:i'),
            'sof_zman_shema' => $sunrise->copy()->addMinutes($shaahZmanit * 3)->format('H:i'),
            'sof_zman_tefillah' => $sunrise->copy()->addMinutes($shaahZmanit * 4)->format('H:i'),
            'chatzot' => $sunrise->copy()->addMinutes($shaahZmanit * 6)->format('H:i'),
            'mincha_gedolah' => $sunrise->copy()->addMinutes($shaahZmanit * 6.5)->format('H:i'),
            'mincha_ketanah' => $sunrise->copy()->addMinutes($shaahZmanit * 9.5)->format('H:i'),
            'plag_hamincha' => $sunrise->copy()->addMinutes($shaahZmanit * 10.75)->format('H:i'),
            'sunset' => $sunset->format('H:i'),
            'tzeis_hakochavim' => $sunset->copy()->addMinutes(45)->format('H:i'),
            'chatzot_layla' => $dateTime->copy()->setTime(0, 0)->format('H:i')
        ];
    }

    private function translateZmanim($zmanim, $lang)
    {
        $translated = [];
        foreach ($zmanim as $key => $time) {
            // Use Hebrew name as primary
            $hebrewName = $this->hebrewNames[$key] ?? $key;
            
            // Add translation for sunrise/sunset only
            $translation = null;
            if (isset($this->translations[$lang][$key])) {
                $translation = $this->translations[$lang][$key];
            }
            
            $translated[] = [
                'key' => $key,
                'name' => $hebrewName,
                'translation' => $translation,
                'time' => $time,
                'sortTime' => $time ? str_replace(':', '', $time) : '9999'
            ];
        }
        
        // Sort by time
        usort($translated, function($a, $b) {
            return strcmp($a['sortTime'], $b['sortTime']);
        });
        
        // Remove sortTime before returning
        array_walk($translated, function(&$item) {
            unset($item['sortTime']);
        });
        
        return $translated;
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
            ]
        ];
        
        return $names[$city][$lang] ?? $city;
    }
}