<?php
class IPPPartnerGraph {
    private $user_id;
    private $session_id;
    private $request;
    private $partner;
    public $data_sources = [];

    function __construct($partner, $request,$id = "",$session_id = "") {
        $this->request = $request;

        if($id != "")
            $this->user_id = $id;
        if($session_id != "")
            $this->session_id = $session_id;
        $this->partner = $partner;

        $this->data_sources = $this->getDataSources();
    }

    private function getDataSources() {
        $sources = [];
        $sources["customers_created_7_days"] = [
            "id"            => "customers_created_7_days",
            "title"         => "Created Customers, past 7 days",
            "source"        => "api",
            "datasource"    => "company",
            "serve"         => "count",
            "period"        => 7,
            "group"         => "start_time",
            "aggregation"   => "daily"
        ];
        $sources["customers_created_30_days"] = [
            "id"            => "customers_created_30_days",
            "title"         => "Created Customers, past 30 days",
            "source"        => "api",
            "datasource"    => "company",
            "serve"         => "count",
            "period"        => 30,
            "group"         => "start_time",
            "aggregation"   => "daily"
        ];
        $sources["customers_accumulated"] = [
            "id"            => "customers_accumulated",
            "title"         => "Customers Accumulated",
            "source"        => "api",
            "datasource"    => "company",
            "serve"         => "accumulated_count",
            "period"        => 0,
            "group"         => "start_time",
            "aggregation"   => "monthly"
        ];
        $sources["transactions_approved_7_days"] = [
            "id"            => "transactions_approved_7_days",
            "title"         => "Approved Transactions, past 7 days",
            "source"        => "api",
            "datasource"    => "transactions",
            "serve"         => "count",
            "period"        => 7,
            "group"         => "start_time",
            "aggregation"   => "daily"
        ];
        $sources["transactions_approved_14_days"] = [
            "id"            => "transactions_approved_14_days",
            "title"         => "Approved Transactions, past 14 days",
            "source"        => "api",
            "datasource"    => "transactions",
            "serve"         => "count",
            "period"        => 14,
            "group"         => "start_time",
            "aggregation"   => "daily"
        ];
        $sources["transactions_approved_30_days"] = [
            "id"            => "transactions_approved_30_days",
            "title"         => "Approved Transactions, past 30 days",
            "source"        => "api",
            "datasource"    => "transactions",
            "serve"         => "count",
            "period"        => 30,
            "group"         => "start_time",
            "aggregation"   => "daily"
        ];
        $sources["transactions_volume_30_days"] = [
            "id"            => "transactions_volume_30_days",
            "title"         => "Transactions Gross Volume, past 30 days",
            "source"        => "api",
            "datasource"    => "volume",
            "serve"         => "sum",
            "period"        => 30,
            "group"         => "transaction_time",
            "aggregation"   => "daily"
        ];
        $sources["transactions_accumulated_volume"] = [
            "id"            => "transactions_accumulated_volume",
            "title"         => "Transactions Gross Volume, Accumulated",
            "source"        => "api",
            "datasource"    => "volume",
            "serve"         => "accumulated_sum",
            "period"        => 0,
            "group"         => "transaction_time",
            "aggregation"   => "monthly"
        ];
        $sources["provision_accumulated_volume"] = [
            "id"            => "provision_accumulated_volume",
            "title"         => "Provision Gross Volume, Accumulated",
            "source"        => "api",
            "datasource"    => "partner_provision",
            "serve"         => "accumulated_sum",
            "period"        => 0,
            "group"         => "created",
            "aggregation"   => "monthly"
        ];
        $sources["provision_last_12_months"] = [
            "id"            => "provision_last_12_months",
            "title"         => "Provision Gross Volume, Last 12 months",
            "source"        => "api",
            "datasource"    => "partner_provision",
            "serve"         => "sum",
            "period"        => 365,
            "group"         => "created",
            "aggregation"   => "monthly"
        ];
        return $sources;
    }

    private function StatisticsRequest($data_table,$period) {
        global $request;
        $dataset    = [];
        $dataset["x"] = "";
        $dataset["y"] = "";
        $data = [
            "since" => (time()-(86400*$period)),
            "table" => $data_table["datasource"],
            "serve" => $data_table["serve"],
            "group" => $data_table["group"].",".$data_table["aggregation"]
        ];
        $r_data = $request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/statistics/", "POST", [], $data)->content;
        foreach($r_data->{$data_table["serve"]} as $key=>$value) {
            $dataset["x"] .= "'".$key."',";
            if(isset($value->sum))
                $dataset["y"] .= "'".$value->sum."',";
            else
                $dataset["y"] .= "'".$value->count."',";
        }
        $dataset["x"] = rtrim($dataset["x"],",");
        $dataset["y"] = rtrim($dataset["y"],",");
        return $dataset;
    }

    public function GenerateHTML($sequence, $Graph, $Type,$live=false) {
        global $inline_script;
        $data = $this->StatisticsRequest($this->data_sources[$Graph],$this->data_sources[$Graph]["period"]);
        $script = '
        window.addEventListener("load", function () {

        var '.$this->data_sources[$Graph]["id"].' = echarts.init(document.getElementById("'.$this->data_sources[$Graph]["id"].'"));
        option = {
            tooltip: {
                trigger: "item",
            },
            xAxis: {
                data: ['.$data["x"].']
            },
            yAxis: {},
            series: [
                {
                    type: \''.$Type.'\',
                    data: ['.$data["y"].']
                }
            ]
        };
            '.$this->data_sources[$Graph]["id"].'.setOption(option);
            console.log("Done");
        });';
        $html = "";
        $html .= "<div class='element' data-sequence='".$sequence."'><h4>".$this->data_sources[$Graph]["title"]."</h4><button class='btn btn-danger DashboardRemoveElement'>Remove</button><div class='graph' id='".$this->data_sources[$Graph]["id"]."'></div></div>";
        if($live)
            echo "<script>".$script."</script>";
        else
            $inline_script[] = $script;
        return $html;
    }
}
