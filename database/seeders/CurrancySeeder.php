<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = array(
            array('symbol' => '$', 'shortname' => 'usd', 'fullname' => 'United States Dollar'),
            array('symbol' => '€', 'shortname' => 'eur', 'fullname' => 'Euro'),
            array('symbol' => '¥', 'shortname' => 'jpy', 'fullname' => 'Japanese Yen'),
            array('symbol' => '£', 'shortname' => 'gbp', 'fullname' => 'British Pound Sterling'),
            array('symbol' => '₹', 'shortname' => 'inr', 'fullname' => 'Indian Rupee'),
            array('symbol' => '₽', 'shortname' => 'rub', 'fullname' => 'Russian Ruble'),
            array('symbol' => 'د.إ', 'shortname' => 'aed', 'fullname' => 'United Arab Emirates Dirham'),
            array('symbol' => 'د.ك', 'shortname' => 'kwd', 'fullname' => 'Kuwaiti Dinar'),
            array('symbol' => 'د.ج', 'shortname' => 'dzd', 'fullname' => 'Algerian Dinar'),
            array('symbol' => 'د.ب', 'shortname' => 'bhd', 'fullname' => 'Bahraini Dinar'),
            array('symbol' => 'د.ع', 'shortname' => 'iqd', 'fullname' => 'Iraqi Dinar'),
            array('symbol' => 'د.م.', 'shortname' => 'mad', 'fullname' => 'Moroccan Dirham'),
            array('symbol' => 'د.ت', 'shortname' => 'tnd', 'fullname' => 'Tunisian Dinar'),
            array('symbol' => 'د.س', 'shortname' => 'sar', 'fullname' => 'Saudi Riyal'),
            array('symbol' => '₱', 'shortname' => 'php', 'fullname' => 'Philippine Peso'),
            array('symbol' => '₮', 'shortname' => 'mnt', 'fullname' => 'Mongolian Tugrik'),
            array('symbol' => 'лв', 'shortname' => 'bgn', 'fullname' => 'Bulgarian Lev'),
            array('symbol' => 'kč', 'shortname' => 'czk', 'fullname' => 'Czech Koruna'),
            array('symbol' => 'kr', 'shortname' => 'sek', 'fullname' => 'Swedish Krona'),
            array('symbol' => 'chf', 'shortname' => 'chf', 'fullname' => 'Swiss Franc'),
            array('symbol' => 'kr', 'shortname' => 'nok', 'fullname' => 'Norwegian Krone'),
            array('symbol' => '₹', 'shortname' => 'pkr', 'fullname' => 'Pakistani Rupee'),
            array('symbol' => 'лв', 'shortname' => 'ron', 'fullname' => 'Romanian Leu'),
            array('symbol' => '₨', 'shortname' => 'lkr', 'fullname' => 'Sri Lankan Rupee'),
            array('symbol' => '₾', 'shortname' => 'gel', 'fullname' => 'Georgian Lari'),
            array('symbol' => '₦', 'shortname' => 'ngn', 'fullname' => 'Nigerian Naira'),
            array('symbol' => '₵', 'shortname' => 'ghs', 'fullname' => 'Ghanaian Cedi'),
            array('symbol' => 'zł', 'shortname' => 'pln', 'fullname' => 'Polish Zloty'),
            array('symbol' => '₼', 'shortname' => 'azn', 'fullname' => 'Azerbaijani Manat'),
            array('symbol' => '₫', 'shortname' => 'vnd', 'fullname' => 'Vietnamese Dong'),
            array('symbol' => 'ریال', 'shortname' => 'irr', 'fullname' => 'Iranian Rial'),
            array('symbol' => '₩', 'shortname' => 'krw', 'fullname' => 'South Korean Won'),
            array('symbol' => '₴', 'shortname' => 'uah', 'fullname' => 'Ukrainian Hryvnia'),
            array('symbol' => 'руб', 'shortname' => 'byn', 'fullname' => 'Belarusian Ruble'),
            array('symbol' => '₺', 'shortname' => 'try', 'fullname' => 'Turkish Lira'),
            array('symbol' => 'r$', 'shortname' => 'brl', 'fullname' => 'Brazilian Real'),
            array('symbol' => '৳', 'shortname' => 'bdt', 'fullname' => 'Bangladeshi Taka'),
            array('symbol' => '₪', 'shortname' => 'ils', 'fullname' => 'Israeli New Shekel'),
            array('symbol' => '₡', 'shortname' => 'crc', 'fullname' => 'Costa Rican Colón'),
            array('symbol' => '₹', 'shortname' => 'npr', 'fullname' => 'Nepalese Rupee'),
            array('symbol' => '៛', 'shortname' => 'khr', 'fullname' => 'Cambodian Riel'),
            array('symbol' => '₨', 'shortname' => 'lkr', 'fullname' => 'Sri Lankan Rupee'),
            array('symbol' => 'bs.', 'shortname' => 'vef', 'fullname' => 'Venezuelan Bolívar'),
            array('symbol' => '₣', 'shortname' => 'xaf', 'fullname' => 'Central African CFA Franc'),
            array('symbol' => 'fcfa', 'shortname' => 'xof', 'fullname' => 'West African CFA Franc'),
            array('symbol' => '₣', 'shortname' => 'chf', 'fullname' => 'Swiss Franc'),
            array('symbol' => '₤', 'shortname' => 'trl', 'fullname' => 'Turkish Lira'),
            array('symbol' => '₧', 'shortname' => 'esp', 'fullname' => 'Spanish Peseta'),
            array('symbol' => 'ft', 'shortname' => 'huf', 'fullname' => 'Hungarian Forint'),
            array('symbol' => '₭', 'shortname' => 'lak', 'fullname' => 'Laotian Kip'),
            array('symbol' => '₰', 'shortname' => 'dem', 'fullname' => 'German Mark'),
            array('symbol' => '₯', 'shortname' => 'grd', 'fullname' => 'Greek Drachma'),
            array('symbol' => '₣', 'shortname' => 'frf', 'fullname' => 'French Franc'),
            array('symbol' => '₤', 'shortname' => 'itl', 'fullname' => 'Italian Lira')
        );        
        \App\Models\Currancy::insert($currencies);
    }
}
