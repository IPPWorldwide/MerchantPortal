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
            case "348":
            case "HUF":
                return ["HUF", "Forint", "348", "HU", "HUN"];
            case "352":
            case "ISK":
                return ["ISK", "Iceland Krona", "352", "IS", "ISL"];
            case "356":
            case "INR":
                return ["INR", "Indian Rupee", "356", "IN", "IND"];
            case "392":
            case "JPY":
                return ["JPY", "Yen", "392", "JP", "JPN"];
            case "578":
            case "NOK":
                return ["NOK", "Norwegian Krone", "578", "NO", "NOR"];
            case "710":
            case "ZAR":
                return ["ZAR", "Rand", "710", "ZA", "ZAF"];
            case "752":
            case "SEK":
                return ["SEK", "Swedish Krona", "752", "SE", "SWE"];
            case "756":
            case "CHF":
                return ["CHF", "Swiss Franc", "756", "CH", "CHE"];
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
            case "978":
            case "EUR":
                return ["EUR", "Euro", "978"];
            case "985":
            case "PLN":
                return ["PLN", "Zloty", "985"];
            default:
                return [];
        }
    }
    public function currency_list(){
        $currencies = [
            "036",
            "124",
            "191",
            "203",
            "208",
            "320",
            "348",
            "352",
            "356",
            "392",
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
            "978",
            "985"
        ];
        asort($currencies,"string");
        return $currencies;
    }

}
