<?php

class IPPCountry {

    public function country($countryId) {
        switch ((String) $countryId) {
            case "DKK":
            case "DNK":
            case "Denmark":
                return ["DK","DNK","DKK", "Denmark"];
            case "SEK":
            case "SWE":
            case "Sweden":
                return ["SE","SWE","SEK", "Sweden"];
            case "NOK":
            case "NOR":
            case "Norway":
                return ["NO","NOR","NOK", "Norway"];
        }
    }
}
