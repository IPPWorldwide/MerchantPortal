<?php
class IPPPartnerGraph {
    private $user_id;
    private $session_id;
    private $request;
    private $partner;

    function __construct($partner, $request,$id = "",$session_id = "") {
        $this->request = $request;

        if($id != "")
            $this->user_id = $id;
        if($session_id != "")
            $this->session_id = $session_id;
        $this->partner = $partner;
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

    public function GenerateHTML(int $i, $time, $period, string $ElementData, string $ElementType) {
        global $lang;
        $html = '<div class="col themed-grid-col chartscol dashboard" data-sequence="'.$i.'" data-data="'.$ElementData.'" data-type="'.$ElementType.'">
            <div class="content">
                <h2>Transactions past '.$time.' ';
                if($period == "d") {
                    $html .= "days";
                } $html .= '</h2>
                <canvas id="chart'.$i.'" height="130px"></canvas>
            </div>
            <div class="settings">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
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

    public function getDataSource($data) {
        if($data==="customers_created_7_days") {
            $datasource = "transactions";
            $time   = 7;
            $period = "d";
        }


        return [
            "source" => $datasource,
            "time"   => $time,
            "length" => $time . $period,
            "period" => $period
        ];
    }

    /*
    public function graph_3($request)
    {
        $graphs = $this->partner->statisticCharts("yearly",$request["type"])->content;
        $data = [];
        $label = [];
        $background = [];
        $border = [];
        foreach($graphs as $value) {
            $label[] = $value->display;
            $data[] = $value->count;
            $background[] = "rgba(255, 99, 132, 0.2)";
            $border[] = "rgba(255, 99, 132)";
        }
        echo json_encode([
            "type" => "bar",
            "data" => [
                "labels" => array_reverse($label),
                "datasets" => array([
                    "label" => 'Transactions',
                    "data" => array_reverse($data),
                    "lineTension" => 0,
                    "backgroundColor" => $background,
                    "borderColor" => $border,
                    "borderWidth" => 1,
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
                    "display" => false
                ]
            ]
        ]);
    }
    public function graphCompare()
    {
        $data = [];
        $data2 = [];
        $label = [];
        $today = new DateTime();
        for($i = 1; $i <= 7 ; $i++){
            $label[] = $today->format("l");
            $today->add(new DateInterval("P1D"));
            $data[] = rand(14000, 50000);
            $data2[] = rand(14000, 50000);
        }
        echo json_encode([
            "type" => "line",
            "data" => [
                "labels" => $label,
                "datasets" => array([
                    "label" => "Data 1",
                    "data" => $data,
                    "backgroundColor" => 'transparent',
                    "borderColor" => '#007bff',
                    "borderWidth" => 2,
                    "pointBackgroundColor" =>'#007bff',
                    "tension" => 0.5,
                ],[
                    "label" => "Data 2",
                    "data" => $data2,
                    "backgroundColor" => 'transparent',
                    "borderColor" => 'rgb(75, 192, 192)',
                    "borderWidth" => 2,
                    "pointBackgroundColor" =>'rgb(75, 192, 192)',
                    "tension" => 0.5
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
        ]);
    }
    */
}
