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
        'sofZmanShmaMGA' => 'סוף זמן ק״ש מג״א',
        'sofZmanShma' => 'סוף זמן ק״ש גר״א',
        'sofZmanTfillaMGA' => 'סוף זמן תפילה מג״א',
        'sofZmanTfilla' => 'סוף זמן תפילה גר״א',
        'chatzot' => 'חצות',
        'minchaGedola' => 'מנחה גדולה',
        'minchaGedolaMGA' => 'מנחה גדולה מג״א',
        'minchaKetana' => 'מנחה קטנה',
        'minchaKetanaMGA' => 'מנחה קטנה מג״א',
        'plagHaMincha' => 'פלג המנחה',
        'sunset' => 'שקיעה',
        'tzeit35min' => 'צאת הכוכבים (35 דקות)',
        'tzeit42min' => 'צאת הכוכבים (42 דקות)',
        'tzeit50min' => 'צאת הכוכבים (50 דקות)',
        'tzeit72min' => 'צאת הכוכבים (72 דקות)',
    ];
    
    // Times to exclude from the response
    private $excludedTimes = [
        'beinHaShmashos',
        'dusk',
        'tzeit7083deg',
        'tzeit85deg',
        'sofZmanShmaMGA19Point8',
        'sofZmanShmaMGA16Point1',
        'sofZmanTfillaMGA19Point8',
        'sofZmanTfillaMGA16Point1'
    ];

    private $translations = [
        'fr' => [
            'chatzotNight' => 'Minuit',
            'alotHaShachar' => 'Aube',
            'misheyakir' => 'Tallit le plus tôt',
            'misheyakirMachmir' => 'Tallit le plus tôt (Strict)',
            'dawn' => 'Aube civile',
            'sunrise' => 'Lever du soleil',
            'sofZmanShmaMGA' => 'Dernier Shema MGA',
            'sofZmanShma' => 'Dernier Shema GRA',
            'sofZmanTfillaMGA' => 'Dernière Tefillah MGA',
            'sofZmanTfilla' => 'Dernière Tefillah GRA',
            'chatzot' => 'Midi',
            'minchaGedola' => 'Minha précoce GRA',
            'minchaGedolaMGA' => 'Minha précoce MGA',
            'minchaKetana' => 'Minha Ketana GRA',
            'minchaKetanaMGA' => 'Minha Ketana MGA',
            'plagHaMincha' => 'Plag HaMinha',
            'sunset' => 'Coucher du soleil',
            'tzeit35min' => 'Tombée de la nuit (35 min)',
            'tzeit42min' => 'Tombée de la nuit (42 min)',
            'tzeit50min' => 'Tombée de la nuit (50 min)',
            'tzeit72min' => 'Tombée de la nuit (72 min)',
        ],
        'en' => [
            'chatzotNight' => 'Midnight',
            'alotHaShachar' => 'Dawn',
            'misheyakir' => 'Earliest Tallit',
            'misheyakirMachmir' => 'Earliest Tallit (Stringent)',
            'dawn' => 'Civil Dawn',
            'sunrise' => 'Sunrise',
            'sofZmanShmaMGA' => 'Latest Shema MGA',
            'sofZmanShma' => 'Latest Shema GRA',
            'sofZmanTfillaMGA' => 'Latest Tefillah MGA',
            'sofZmanTfilla' => 'Latest Tefillah GRA',
            'chatzot' => 'Midday',
            'minchaGedola' => 'Earliest Mincha GRA',
            'minchaGedolaMGA' => 'Earliest Mincha MGA',
            'minchaKetana' => 'Mincha Ketana GRA',
            'minchaKetanaMGA' => 'Mincha Ketana MGA',
            'plagHaMincha' => 'Plag HaMincha',
            'sunset' => 'Sunset',
            'tzeit35min' => 'Nightfall (35 min)',
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
            'sofZmanShmaMGA' => 'Último Shemá MGA',
            'sofZmanShma' => 'Último Shemá GRA',
            'sofZmanTfillaMGA' => 'Última Tefilá MGA',
            'sofZmanTfilla' => 'Última Tefilá GRA',
            'chatzot' => 'Mediodía',
            'minchaGedola' => 'Minjá temprana GRA',
            'minchaGedolaMGA' => 'Minjá temprana MGA',
            'minchaKetana' => 'Minjá Ketaná GRA',
            'minchaKetanaMGA' => 'Minjá Ketaná MGA',
            'plagHaMincha' => 'Plag HaMinjá',
            'sunset' => 'Puesta del sol',
            'tzeit35min' => 'Anochecer (35 min)',
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
            'sofZmanShmaMGA' => 'آخر وقت شيما MGA',
            'sofZmanShma' => 'آخر وقت شيما GRA',
            'sofZmanTfillaMGA' => 'آخر وقت الصلاة MGA',
            'sofZmanTfilla' => 'آخر وقت الصلاة GRA',
            'chatzot' => 'منتصف النهار',
            'minchaGedola' => 'مينحا المبكرة GRA',
            'minchaGedolaMGA' => 'مينحا المبكرة MGA',
            'minchaKetana' => 'مينحا كيتانا GRA',
            'minchaKetanaMGA' => 'مينحا كيتانا MGA',
            'plagHaMincha' => 'بلاغ هامينحا',
            'sunset' => 'غروب الشمس',
            'tzeit35min' => 'حلول الليل (35 دقيقة)',
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
            'lang' => 'nullable|string|in:en,es,he,ar,fr',
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
        $sunsetTime = null;
        foreach ($times as $key => $timeString) {
            // Skip excluded times
            if (in_array($key, $this->excludedTimes)) {
                continue;
            }
            
            // Parse the time and format it as HH:MM
            $carbonTime = Carbon::parse($timeString);
            $formattedTime = $carbonTime->format('H:i');
            
            // Store sunset time for calculating 35 minutes
            if ($key === 'sunset') {
                $sunsetTime = $carbonTime;
            }
            
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
        
        // Add Tzet 35 minutes if we have sunset time
        if ($sunsetTime) {
            $tzeit35 = $sunsetTime->copy()->addMinutes(35);
            $formattedTime = $tzeit35->format('H:i');
            
            $translation = null;
            if ($lang !== 'he' && isset($this->translations[$lang]['tzeit35min'])) {
                $translation = $this->translations[$lang]['tzeit35min'];
            }
            
            $sortedTimes[$formattedTime . '_tzeit35min'] = [
                'key' => 'tzeit35min',
                'name' => $this->hebrewNames['tzeit35min'],
                'translation' => $translation,
                'time' => $formattedTime,
            ];
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
        $search = $request->search ?? '';
        
        // Extended list of Jewish communities worldwide
        $allLocations = [
            // Israel
            ['id' => 'jerusalem', 'name' => $this->getLocationName('Jerusalem', $lang), 'latitude' => 31.7683, 'longitude' => 35.2137, 'timezone' => 'Asia/Jerusalem', 'country' => 'Israel'],
            ['id' => 'tel-aviv', 'name' => $this->getLocationName('Tel Aviv', $lang), 'latitude' => 32.0853, 'longitude' => 34.7818, 'timezone' => 'Asia/Jerusalem', 'country' => 'Israel'],
            ['id' => 'haifa', 'name' => 'Haifa', 'latitude' => 32.7940, 'longitude' => 34.9896, 'timezone' => 'Asia/Jerusalem', 'country' => 'Israel'],
            ['id' => 'bnei-brak', 'name' => 'Bnei Brak', 'latitude' => 32.0807, 'longitude' => 34.8338, 'timezone' => 'Asia/Jerusalem', 'country' => 'Israel'],
            ['id' => 'ashdod', 'name' => 'Ashdod', 'latitude' => 31.8044, 'longitude' => 34.6553, 'timezone' => 'Asia/Jerusalem', 'country' => 'Israel'],
            ['id' => 'beer-sheva', 'name' => 'Beer Sheva', 'latitude' => 31.2530, 'longitude' => 34.7915, 'timezone' => 'Asia/Jerusalem', 'country' => 'Israel'],
            ['id' => 'eilat', 'name' => 'Eilat', 'latitude' => 29.5577, 'longitude' => 34.9519, 'timezone' => 'Asia/Jerusalem', 'country' => 'Israel'],
            ['id' => 'tiberias', 'name' => 'Tiberias', 'latitude' => 32.7922, 'longitude' => 35.5312, 'timezone' => 'Asia/Jerusalem', 'country' => 'Israel'],
            ['id' => 'tzfat', 'name' => 'Tzfat (Safed)', 'latitude' => 32.9658, 'longitude' => 35.4983, 'timezone' => 'Asia/Jerusalem', 'country' => 'Israel'],
            
            // USA
            ['id' => 'new-york', 'name' => $this->getLocationName('New York', $lang), 'latitude' => 40.7128, 'longitude' => -74.0060, 'timezone' => 'America/New_York', 'country' => 'USA'],
            ['id' => 'brooklyn', 'name' => 'Brooklyn', 'latitude' => 40.6782, 'longitude' => -73.9442, 'timezone' => 'America/New_York', 'country' => 'USA'],
            ['id' => 'lakewood', 'name' => 'Lakewood, NJ', 'latitude' => 40.0978, 'longitude' => -74.2176, 'timezone' => 'America/New_York', 'country' => 'USA'],
            ['id' => 'monsey', 'name' => 'Monsey, NY', 'latitude' => 41.1112, 'longitude' => -74.0685, 'timezone' => 'America/New_York', 'country' => 'USA'],
            ['id' => 'los-angeles', 'name' => $this->getLocationName('Los Angeles', $lang), 'latitude' => 34.0522, 'longitude' => -118.2437, 'timezone' => 'America/Los_Angeles', 'country' => 'USA'],
            ['id' => 'miami', 'name' => $this->getLocationName('Miami', $lang), 'latitude' => 25.7617, 'longitude' => -80.1918, 'timezone' => 'America/New_York', 'country' => 'USA'],
            ['id' => 'chicago', 'name' => 'Chicago', 'latitude' => 41.8781, 'longitude' => -87.6298, 'timezone' => 'America/Chicago', 'country' => 'USA'],
            ['id' => 'boston', 'name' => 'Boston', 'latitude' => 42.3601, 'longitude' => -71.0589, 'timezone' => 'America/New_York', 'country' => 'USA'],
            ['id' => 'baltimore', 'name' => 'Baltimore', 'latitude' => 39.2904, 'longitude' => -76.6122, 'timezone' => 'America/New_York', 'country' => 'USA'],
            ['id' => 'detroit', 'name' => 'Detroit', 'latitude' => 42.3314, 'longitude' => -83.0458, 'timezone' => 'America/Detroit', 'country' => 'USA'],
            ['id' => 'cleveland', 'name' => 'Cleveland', 'latitude' => 41.4993, 'longitude' => -81.6944, 'timezone' => 'America/New_York', 'country' => 'USA'],
            ['id' => 'atlanta', 'name' => 'Atlanta', 'latitude' => 33.7490, 'longitude' => -84.3880, 'timezone' => 'America/New_York', 'country' => 'USA'],
            ['id' => 'houston', 'name' => 'Houston', 'latitude' => 29.7604, 'longitude' => -95.3698, 'timezone' => 'America/Chicago', 'country' => 'USA'],
            ['id' => 'dallas', 'name' => 'Dallas', 'latitude' => 32.7767, 'longitude' => -96.7970, 'timezone' => 'America/Chicago', 'country' => 'USA'],
            ['id' => 'denver', 'name' => 'Denver', 'latitude' => 39.7392, 'longitude' => -104.9903, 'timezone' => 'America/Denver', 'country' => 'USA'],
            ['id' => 'phoenix', 'name' => 'Phoenix', 'latitude' => 33.4484, 'longitude' => -112.0740, 'timezone' => 'America/Phoenix', 'country' => 'USA'],
            ['id' => 'seattle', 'name' => 'Seattle', 'latitude' => 47.6062, 'longitude' => -122.3321, 'timezone' => 'America/Los_Angeles', 'country' => 'USA'],
            ['id' => 'san-francisco', 'name' => 'San Francisco', 'latitude' => 37.7749, 'longitude' => -122.4194, 'timezone' => 'America/Los_Angeles', 'country' => 'USA'],
            
            // Europe
            ['id' => 'london', 'name' => $this->getLocationName('London', $lang), 'latitude' => 51.5074, 'longitude' => -0.1278, 'timezone' => 'Europe/London', 'country' => 'UK'],
            ['id' => 'manchester', 'name' => 'Manchester', 'latitude' => 53.4808, 'longitude' => -2.2426, 'timezone' => 'Europe/London', 'country' => 'UK'],
            ['id' => 'paris', 'name' => $this->getLocationName('Paris', $lang), 'latitude' => 48.8566, 'longitude' => 2.3522, 'timezone' => 'Europe/Paris', 'country' => 'France'],
            ['id' => 'antwerp', 'name' => 'Antwerp', 'latitude' => 51.2194, 'longitude' => 4.4025, 'timezone' => 'Europe/Brussels', 'country' => 'Belgium'],
            ['id' => 'amsterdam', 'name' => 'Amsterdam', 'latitude' => 52.3676, 'longitude' => 4.9041, 'timezone' => 'Europe/Amsterdam', 'country' => 'Netherlands'],
            ['id' => 'zurich', 'name' => 'Zurich', 'latitude' => 47.3769, 'longitude' => 8.5417, 'timezone' => 'Europe/Zurich', 'country' => 'Switzerland'],
            ['id' => 'vienna', 'name' => 'Vienna', 'latitude' => 48.2082, 'longitude' => 16.3738, 'timezone' => 'Europe/Vienna', 'country' => 'Austria'],
            
            // Canada
            ['id' => 'montreal', 'name' => $this->getLocationName('Montreal', $lang), 'latitude' => 45.5017, 'longitude' => -73.5673, 'timezone' => 'America/Montreal', 'country' => 'Canada'],
            ['id' => 'toronto', 'name' => 'Toronto', 'latitude' => 43.6532, 'longitude' => -79.3832, 'timezone' => 'America/Toronto', 'country' => 'Canada'],
            
            // Latin America
            ['id' => 'mexico-city', 'name' => 'Mexico City', 'latitude' => 19.4326, 'longitude' => -99.1332, 'timezone' => 'America/Mexico_City', 'country' => 'Mexico'],
            ['id' => 'buenos-aires', 'name' => 'Buenos Aires', 'latitude' => -34.6037, 'longitude' => -58.3816, 'timezone' => 'America/Argentina/Buenos_Aires', 'country' => 'Argentina'],
            ['id' => 'sao-paulo', 'name' => 'São Paulo', 'latitude' => -23.5505, 'longitude' => -46.6333, 'timezone' => 'America/Sao_Paulo', 'country' => 'Brazil'],
            ['id' => 'rio-de-janeiro', 'name' => 'Rio de Janeiro', 'latitude' => -22.9068, 'longitude' => -43.1729, 'timezone' => 'America/Sao_Paulo', 'country' => 'Brazil'],
            
            // Australia
            ['id' => 'sydney', 'name' => 'Sydney', 'latitude' => -33.8688, 'longitude' => 151.2093, 'timezone' => 'Australia/Sydney', 'country' => 'Australia'],
            ['id' => 'melbourne', 'name' => 'Melbourne', 'latitude' => -37.8136, 'longitude' => 144.9631, 'timezone' => 'Australia/Melbourne', 'country' => 'Australia'],
            
            // South Africa
            ['id' => 'johannesburg', 'name' => 'Johannesburg', 'latitude' => -26.2041, 'longitude' => 28.0473, 'timezone' => 'Africa/Johannesburg', 'country' => 'South Africa'],
            ['id' => 'cape-town', 'name' => 'Cape Town', 'latitude' => -33.9249, 'longitude' => 18.4241, 'timezone' => 'Africa/Johannesburg', 'country' => 'South Africa'],
        ];
        
        // If no search term, return popular locations
        if (empty($search)) {
            // Return the first 10 as defaults
            $defaultLocations = array_slice($allLocations, 0, 10);
            return response()->json([
                'status' => 'success',
                'locations' => $defaultLocations
            ]);
        }
        
        // Filter locations by search term
        $searchLower = strtolower($search);
        $filtered = array_filter($allLocations, function($location) use ($searchLower) {
            return stripos($location['name'], $searchLower) !== false ||
                   stripos($location['country'], $searchLower) !== false ||
                   stripos($location['id'], $searchLower) !== false;
        });
        
        // If we have results, return them
        if (!empty($filtered)) {
            return response()->json([
                'status' => 'success',
                'locations' => array_values($filtered)
            ]);
        }
        
        // If no results found, return empty array with message
        return response()->json([
            'status' => 'success',
            'locations' => [],
            'message' => 'No locations found matching your search'
        ]);
    }
    
    private function getOsmLanguage($lang)
    {
        $osmLangs = [
            'en' => 'en',
            'es' => 'es',
            'he' => 'he',
            'ar' => 'ar'
        ];
        
        return $osmLangs[$lang] ?? 'en';
    }
    
    private function estimateTimezone($longitude, $latitude)
    {
        // Common timezone mappings based on regions
        // Israel
        if ($longitude >= 34 && $longitude <= 36 && $latitude >= 29 && $latitude <= 34) {
            return 'Asia/Jerusalem';
        }
        
        // USA East Coast
        if ($longitude >= -82 && $longitude <= -70 && $latitude >= 24 && $latitude <= 48) {
            return 'America/New_York';
        }
        
        // USA West Coast
        if ($longitude >= -125 && $longitude <= -115 && $latitude >= 32 && $latitude <= 49) {
            return 'America/Los_Angeles';
        }
        
        // USA Central
        if ($longitude >= -100 && $longitude <= -82 && $latitude >= 25 && $latitude <= 50) {
            return 'America/Chicago';
        }
        
        // UK
        if ($longitude >= -8 && $longitude <= 2 && $latitude >= 50 && $latitude <= 60) {
            return 'Europe/London';
        }
        
        // France/Western Europe
        if ($longitude >= -5 && $longitude <= 10 && $latitude >= 42 && $latitude <= 52) {
            return 'Europe/Paris';
        }
        
        // Canada Eastern
        if ($longitude >= -80 && $longitude <= -52 && $latitude >= 43 && $latitude <= 55) {
            return 'America/Toronto';
        }
        
        // Simple UTC offset estimation based on longitude
        $offset = round($longitude / 15);
        if ($offset == 0) {
            return 'UTC';
        } elseif ($offset > 0) {
            return 'Etc/GMT-' . abs($offset); // Note: Etc zones have inverted signs
        } else {
            return 'Etc/GMT+' . abs($offset);
        }
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
            ]
        ];
        
        return $names[$city][$lang] ?? $city;
    }
}