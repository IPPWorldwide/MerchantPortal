<?php

class IPPCurrency {

    public function currency($valutaId) {
        switch ((String) $valutaId) {
            case "036":
            case "AUD":
                return ["AUD", "Australian Dollar", "036", "AU", "AUS"];
            case "124":
            case "CAD":
                return ["CAD", "Canadian Dollar", "124", "CA", "CAN"];
            case "191":
            case "HRK":
                return ["HRK", "Hong Kong Dollar", "191", "HR", "HRV"];
            case "203":
            case "CZK":
                return ["CZK", "Croatian Kuna", "203", "CZ", "CZK"];
            case "320":
            case "GTQ":
                return ["GTQ", "Quetzal", "320", "GT" , "GTM"];
            case "344":
            case "HKD":
                return ["HKD", "Kong Kong Dollar", "344", "HK", "HKG"];
            case "348":
            case "HUF":
                return ["HUF", "Forint", "348", "HU", "HUN"];
            case "352":
            case "ISK":
                return ["ISK", "Iceland Krona", "352", "IS", "ISL"];
            case "392":
            case "JPY":
                return ["JPY", "Yen", "392", "JP", "JPN"];
            case "484":
            case "MXN":
                return ["MXN", "Mexican Peso", "484", "MX", "MEX"];
            case "554":
            case "NZD":
                return ["NZD", "New Zealand Dollar", "554", "NZ", "NZL"];
            case "578":
            case "NOK":
                return ["NOK", "Norwegian Krone", "578", "NO", "NOR"];
            case "702":
            case "SGD":
                return ["SGD", "Singapore Dollar", "702", "SG", "SGP"];
            case "710":
            case "ZAR":
                return ["ZAR", "Rand", "710", "ZA", "ZAF"];
            case "752":
            case "SEK":
                return ["SEK", "Swedish Krona", "752", "SE", "SWE"];
            case "756":
            case "CHF":
                return ["CHF", "Swiss Franc", "756", "CH", "CHE"];
            case "764":
            case "THB":
                return ["THB", "Baha", "764", "TH", "THA"];
            case "826":
            case "GBP":
                return ["GBP", "Pound Sterling", "826", "GB", "GBR"];
            case "840":
            case "USD":
                return ["USD", "US Dollar", "840", "US", "USA"];
            case "941":
            case "RSD":
                return ["RSD", "Serbian Dinar", "941", "RS", "SRB"];
            case "949":
            case "TRY":
                return ["TRY", "New Turkish Lira", "949", "TR", "TUR"];
            case "977":
            case "BAM":
                return ["BAM", "Convertible Marks", "977", "BA", "BIH"];
            case "978":
            case "EUR":
                return ["EUR", "Euro", "978"];
            case "985":
            case "PLN":
                return ["PLN", "Zloty", "985"];
            case "986":
            case "BRL":
                return ["BRL", "Brazilian Real", "986", "BZ", "BR"];
            case "356":
            case "INR":
                return ["INR", "Indian Rupee", "356", "IN", "IND"];
            default:
                return ["DKK", "Danish Krone", "208", "DK", "DNK"];
        }
    }
    public function currency_list(){
        $currencies = [
            "036",
            "124",
            "191",
            "203",
            "320",
            "344",
            "348",
            "352",
            "392",
            "484",
            "554",
            "578",
            "702",
            "710",
            "752",
            "756",
            "764",
            "826",
            "840",
            "941",
            "949",
            "977",
            "978",
            "986",
            "356",
            "208"
        ];
        asort($currencies,"string");
        return $currencies;
    }

}
