<?php
class IPPMenu {

    public $std_menu = [];

    public function __construct()
    {
        $this->std_menu = json_decode($this->set_menu(),true);
    }

    private function set_menu() {
        $menu = [];
        $menu["PARTNER"]["dashboard"] = "Dashboard";
        $menu["PARTNER"]["companies"] = "Companies";
        $menu["PARTNER"]["invoices"] = "Companies Invoices";
        $menu["PARTNER"]["invoices/plans/"] = "Companies Recurring Plans";
        $menu["PARTNER"]["data"] = "Partner Data";
        $menu["PARTNER"]["users"] = "Users";
        $menu["PARTNER"]["plugins"] = "Plugins";
        $menu["PARTNER"]["communications"] = "Outbound Communications";
        $menu["PARTNER"]["menus"] = "Menu Administration";

        $menu["COMPANY"]["dashboard"] = "Dashboard";
        $menu["COMPANY"]["charts"] = "Charts";
        $menu["COMPANY"]["subscriptions"] = "Cardholder Subscriptions";
        $menu["COMPANY"]["virtual_terminal"] = "Virtual Terminal";
        $menu["COMPANY"]["merchant_data"] = "Merchant Data";
        $menu["COMPANY"]["onboarding"] = "Onboarding";
        $menu["COMPANY"]["payouts"] = "Payouts";
        $menu["COMPANY"]["users"] = "Users";
        $menu["COMPANY"]["disputes"] = "Disputes";
        $menu["COMPANY"]["payment_links"] = "Payment Links";
        $menu["COMPANY"]["invoices"] = "Invoices";

        return json_encode($menu);
    }

    public function company_menu() {
        global $IPP_CONFIG;
        return json_decode($IPP_CONFIG["MENU"])->COMPANY;

    }

    public function partner_menu() {
        global $IPP_CONFIG;
        return json_decode($IPP_CONFIG["MENU"])->PARTNER;
    }

    public function menu($section) {
        if($section == "partner")
            return $this->partner_menu();
        elseif($section == "company")
            return $this->company_menu();
    }
}