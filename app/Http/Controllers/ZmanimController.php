<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ZmanimController extends Controller
{
    private $translations = [
        'en' => [
            'alot_hashachar' => 'Dawn',
            'misheyakir' => 'Earliest Tallit',
            'sunrise' => 'Sunrise',
            'sof_zman_shema' => 'Latest Shema',
            'sof_zman_tefillah' => 'Latest Prayer',
            'chatzot' => 'Midday',
            'mincha_gedolah' => 'Earliest Mincha',
            'mincha_ketanah' => 'Mincha Ketana',
            'plag_hamincha' => 'Plag HaMincha',
            'sunset' => 'Sunset',
            'tzeis_hakochavim' => 'Nightfall',
            'chatzot_layla' => 'Midnight'
        ],
        'es' => [
            'alot_hashachar' => 'Amanecer',
            'misheyakir' => 'Talit Más Temprano',
            'sunrise' => 'Salida del Sol',
            'sof_zman_shema' => 'Último Shemá',
            'sof_zman_tefillah' => 'Última Oración',
            'chatzot' => 'Mediodía',
            'mincha_gedolah' => 'Minjá Temprana',
            'mincha_ketanah' => 'Minjá Pequeña',
            'plag_hamincha' => 'Plag HaMinjá',
            'sunset' => 'Puesta del Sol',
            'tzeis_hakochavim' => 'Anochecer',
            'chatzot_layla' => 'Medianoche'
        ],
        'he' => [
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
        ],
        'yi' => [
            'alot_hashachar' => 'עלות השחר',
            'misheyakir' => 'משיכיר',
            'sunrise' => 'זונען אויפגאנג',
            'sof_zman_shema' => 'סוף זמן קריאת שמע',
            'sof_zman_tefillah' => 'סוף זמן תפילה',
            'chatzot' => 'חצות',
            'mincha_gedolah' => 'מנחה גדולה',
            'mincha_ketanah' => 'מנחה קטנה',
            'plag_hamincha' => 'פלג המנחה',
            'sunset' => 'זונען אונטערגאנג',
            'tzeis_hakochavim' => 'צאת הכוכבים',
            'chatzot_layla' => 'חצות לילה'
        ],
        'ar' => [
            'alot_hashachar' => 'الفجر',
            'misheyakir' => 'أقرب وقت للطاليت',
            'sunrise' => 'شروق الشمس',
            'sof_zman_shema' => 'آخر وقت للشيما',
            'sof_zman_tefillah' => 'آخر وقت للصلاة',
            'chatzot' => 'منتصف النهار',
            'mincha_gedolah' => 'منحة الكبرى',
            'mincha_ketanah' => 'منحة الصغرى',
            'plag_hamincha' => 'بلاغ المنحة',
            'sunset' => 'غروب الشمس',
            'tzeis_hakochavim' => 'ظهور النجوم',
            'chatzot_layla' => 'منتصف الليل'
        ]
    ];

    public function getZmanim(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'date' => 'nullable|date',
            'timezone' => 'nullable|string',
            'lang' => 'nullable|in:en,es,he,yi,ar'
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
            $translated[] = [
                'key' => $key,
                'name' => $this->translations[$lang][$key] ?? $key,
                'time' => $time
            ];
        }
        return $translated;
    }

    public function getLocations(Request $request)
    {
        $lang = $request->lang ?? 'en';
        
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
                'yi' => 'ירושלים',
                'ar' => 'القدس'
            ],
            'New York' => [
                'en' => 'New York',
                'es' => 'Nueva York',
                'he' => 'ניו יורק',
                'yi' => 'ניו יארק',
                'ar' => 'نيويورك'
            ],
            'London' => [
                'en' => 'London',
                'es' => 'Londres',
                'he' => 'לונדון',
                'yi' => 'לאנדאן',
                'ar' => 'لندن'
            ],
            'Tel Aviv' => [
                'en' => 'Tel Aviv',
                'es' => 'Tel Aviv',
                'he' => 'תל אביב',
                'yi' => 'תל אביב',
                'ar' => 'تل أبيب'
            ],
            'Los Angeles' => [
                'en' => 'Los Angeles',
                'es' => 'Los Ángeles',
                'he' => 'לוס אנג\'לס',
                'yi' => 'לאס אנדזשעלעס',
                'ar' => 'لوس أنجلوس'
            ]
        ];
        
        return $names[$city][$lang] ?? $city;
    }
}