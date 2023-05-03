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

    public function GenerateView($ViewType="graph",$datasource="tansactions",$length="7d") {
        $graphs = $this->partner->statisticCharts("daily",$datasource,$length)->content;
        $data = [];
        $label = [];
        foreach($graphs as $value) {
            $label[] = $value->display;
            $data[] = $value->count;
        }
        if($ViewType==="GraphLine") {
            return $this->generateGraphJson($label, "Transactions",$data);
        }
        elseif($ViewType==="Number") {
            return $this->generateNumberJson($label, "Transactions",$data);
        }
        else
            return [];
    }

    public function GenerateHTML(int $i, $title, string $ElementData, string $ElementType) {
        global $lang;
        $html = '<div class="col themed-grid-col chartscol dashboard" data-sequence="'.$i.'" data-data="'.$ElementData.'" data-type="'.$ElementType.'">
            <div class="content">
                <h2>'.$title.'</h2>
                <canvas id="chart'.$i.'" height="130px"></canvas>
            </div>
            <div class="settings">
                <button type="button" class="btn btn-warning DashboardRemoveElement">
                  '.$lang["PARTNER"]["DASHBOARD"]["CHANGE_ELEMENT"].'
                </button>
            </div>
        </div>';
        return $html;
    }

    private function generateNumberJson($label,$text,$data) {
        $count = 0;
        foreach($data as $value)
            $count+=$value;
        echo json_encode([
            "type" => "number",
            "data" => [
                "label"  => $text,
                "number" => $count,
            ],
        ],JSON_THROW_ON_ERROR);
    }

    private function generateGraphJson($label, $text, $data, $background='transparent', $border_color='#007bff', $border_width=2, $pointBackground='#007bff', $tension="0.5") {
        echo json_encode([
            "type" => "line",
            "data" => [
                "labels" => array_reverse($label),
                "datasets" => array([
                    "label" => $text,
                    "data" => array_reverse($data),
                    "backgroundColor" => $background,
                    "borderColor" => $border_color,
                    "borderWidth" => $border_width,
                    "pointBackgroundColor" => $pointBackground,
                    "tension" => $tension,
                ])
            ],
            "options" => [
                "scales" => [
                    "yAxes" => [
                        [
                            "ticks" => [
                                "beginAtZero" => false
                            ]
                        ]
                    ]
                ],
                "legend" => [
                    "display" => true
                ],
                "interaction" => [
                    "intersect" => false,
                ],
            ]
        ], JSON_THROW_ON_ERROR, 256);
    }

    private function getDataSources() {
        $sources = [];
        $sources["customers_created_7_days"] = [
            "id"            => "customers_created_7_days",
            "title"         => "Created Customers, past 7 days",
            "datasource"    => "company",
            "time"          => 7,
            "period"        => "d"
        ];
        $sources["customers_created_30_days"] = [
            "id"            => "customers_created_30_days",
            "title"         => "Created Customers, past 30 days",
            "datasource"    => "company",
            "time"          => 30,
            "period"        => "d"
        ];
        $sources["transactions_approved_7_days"] = [
            "id"            => "transactions_approved_7_days",
            "title"         => "Approved Transactions, past 7 days",
            "datasource"    => "transactions",
            "time"          => 7,
            "period"        => "d"
        ];
        $sources["transactions_approved_14_days"] = [
            "id"            => "transactions_approved_14_days",
            "title"         => "Approved Transactions, past 14 days",
            "datasource"    => "transactions",
            "time"          => 14,
            "period"        => "d"
        ];
        $sources["transactions_approved_30_days"] = [
            "id"            => "transactions_approved_30_days",
            "title"         => "Approved Transactions, past 30 days",
            "datasource"    => "transactions",
            "time"          => 30,
            "period"        => "d"
        ];
        return $sources;
    }

    public function getDataSource($data) {
        $source_list = $this->getDataSources();
        $title = $source_list[$data]["title"];
        $datasource = $source_list[$data]["datasource"];
        $time = $source_list[$data]["time"];
        $period = $source_list[$data]["period"];
        return [
            "title"  => $title,
            "source" => $datasource,
            "time"   => $time,
            "length" => $time . $period,
            "period" => $period
        ];
    }
}
