<?php

use Illuminate\Database\Seeder;
use App\Models\RiskRank;
use App\Models\Exam;
use App\Models\ExamIssue;
use App\Models\ExamIssueDetail;

class ExamIssuesSeeder extends Seeder
{
  const EXAM_ARRAY = [
    [
      'exam_code' => 1,
      'name' => '防災',
      'risk_rank_id' => RiskRank::RANK_IDS['rankS'],
      'is_spot' => false,
      'interval' => 1,
      'file_name' => 'exams/1/img_bousai.jpg',
      'icon_name' => 'exams/1/fire_extinguisher_white.svg',
      'color' => '#ea6254',
      'issues' => [
        [
          'name' => '最重要項目',
          'judgement_base' => "避難を妨げる物が存置されていないか\n非常扉\n防火シャッター\n防火扉等",
          'details' => [
            [
              'issue_content' => '非常扉前物品存置',
              'frequency' => 'narrow',
            ],
            [
              'issue_content' => '防火扉前物品存置',
              'frequency' => 'narrow',
            ],
            [
              'issue_content' => '防火シャッター降下物品存置',
              'frequency' => 'narrow',
            ],
            [
              'issue_content' => '竪穴区画内物品存置',
              'frequency' => 'narrow',
            ],
            [
              'issue_content' => '避難階段内物品存置',
              'frequency' => 'narrow',
            ],
          ],
        ],
        [
          'name' => '避難経路',
          'judgement_base' => "陳列商品が避難経路内にはみ出ていないか\n長時間荷台等が存置されていないか",
          'details' => [
            [
              'issue_content' => '幅員不足',
              'frequency' => 'wide',
            ],
          ],
        ],
        [
          'name' => '誘導灯',
          'judgement_base' => "誘導灯がPOPその他物品等で視認障害を起こしていないか\n誘導灯が点灯しているか",
          'details' => [
            [
              'issue_content' => '視認障害',
              'frequency' => 'narrow',

            ],
            [
              'issue_content' => '不点灯',
              'frequency' => 'narrow',
            ],
          ],
        ],
        [
          'name' => 'スプリンクラー',
          'judgement_base' => "設備ヘッドから半径６０センチ以内に障害物がないか",
          'details' => [
            [
              'issue_content' => '散水障害',
              'frequency' => 'wide',
            ],
          ],
        ],
        [
          'name' => 'その他消防設備',
          'judgement_base' => "消火栓、消火器、散水栓その他消防設備の前に物を置いていないか\n",
          'details' => [
            [
              'issue_content' => '物品存置',
              'frequency' => 'wide',
            ],
            [
              'issue_content' => '未設置',
              'frequency' => 'narrow',
            ],
          ],
        ],
        [
          'name' => '電気系統',
          'judgement_base' => "タコ足配線、電源コードの踏みつけ、損傷等発火のリスクを放置していないか",
          'details' => [
            [
              'issue_content' => 'タコ足配線',
              'frequency' => 'wide',
            ],
            [
              'issue_content' => '電源コードの損傷・踏みつけ',
              'frequency' => 'narrow',
            ],
            [
              'issue_content' => '社内規定にないタップの使用',
              'frequency' => 'narrow',
            ],
          ],
        ]
      ]
    ],
    [
      'exam_code' => 2,
      'name' => '食品',
      'risk_rank_id' => RiskRank::RANK_IDS['rankS'],
      'is_spot' => false,
      'interval' => 1,
      'file_name' => 'exams/2/img_shokuhin.jpg',
      'icon_name' => 'exams/2/milk_white.svg',
      'color' => '#28b7aa',
      'issues' => [
        [
          'name' => '冷ケース設備',
          'judgement_base' => "冷ケースの設備故障が起こっていないか\n電灯切れによる見栄え等",
          'details' => [
            [
              'issue_content' => '冷ケースの電灯が切れている',
              'frequency' => 'middle',
            ],
            [
              'issue_content' => '冷ケースの故障',
              'frequency' => 'middle',
            ],
          ],
        ],
        [
          'name' => '温度管理',
          'judgement_base' => "要冷商品を適切な温度で管理しているか\n温度管理表を冷ケース上部に貼付し、定時に運用しているか\n要冷蔵：１０℃以下\n要冷凍：ー１５℃以下",
          'details' => [
            [
              'issue_content' => '温度管理表の未設置',
              'frequency' => 'middle',
            ],
            [
              'issue_content' => '温度管理表の記入不備',
              'frequency' => 'wide',
            ],
            [
              'issue_content' => '温度が基準値を超えている',
              'frequency' => 'narrow',
            ],
          ],
        ],
        [
          'name' => '許可証',
          'judgement_base' => "営業許可証をお客様の見える位置に掲示しているか\n有効期限が切れていないか",
          'details' => [
            [
              'issue_content' => '許可証未掲示',
              'frequency' => 'narrow',
            ],
            [
              'issue_content' => '許可証の有効期限切れ',
              'frequency' => 'narrow',
            ],
          ],
        ],
        [
          'name' => '酒類販売',
          'judgement_base' => "「酒類販売コーナー」専用のPOPを掲示しているか\n１ゴンドラに最低１枚掲示していること",
          'details' => [
            [
              'issue_content' => '酒類専用POP未掲示',
              'frequency' => 'narrow',
            ],
            [
              'issue_content' => '酒類専用POP不足',
              'frequency' => 'middle',
            ],
          ],
        ],
      ]
    ],
    [
      'exam_code' => 3,
      'name' => '高度医療',
      'risk_rank_id' => RiskRank::RANK_IDS['rankA'],
      'is_spot' => false,
      'interval' => 2,
      'file_name' => 'exams/3/img_kodoiryo.jpg',
      'icon_name' => 'exams/3/eye_white.svg',
      'color' => '#5a91ce',
      'issues' => [
        [
          'name' => '販売許可証',
          'judgement_base' => "営業許可証をお客様の見える位置に掲示しているか\n有効期限が切れていないか",
          'details' => [
            [
              'issue_content' => '許可証未掲示',
              'frequency' => 'narrow',
            ],
            [
              'issue_content' => '許可証の有効期限切れ',
              'frequency' => 'narrow',
            ],
          ],
        ],
        [
          'name' => 'コンタクトレンズの保管',
          'judgement_base' => "保管場所に温度計を設置しているか",
          'details' => [
            [
              'issue_content' => '温度計の未設置',
              'frequency' => 'middle',
            ],
          ],
        ],
        [
          'name' => '適正使用に関する情報提供等の徹底',
          'judgement_base' => "レジ内に情報提供用紙を設置しているか\nお客様に記入を促し、記入漏れがないか",
          'details' => [
            [
              'issue_content' => 'レジに情報提供用紙未設置',
              'frequency' => 'middle',
            ],
            [
              'issue_content' => 'お客様同意欄の記入漏れ',
              'frequency' => 'middle',
            ],
          ],
        ],
        [
          'name' => '継続的研修の受講状況',
          'judgement_base' => "継続的研修の受講を受けているか\n受講したことを記録し、帳簿に保管しているか\n記入漏れはないか",
          'details' => [
            [
              'issue_content' => '受講記録に関する帳簿がない',
              'frequency' => 'middle',
            ],
            [
              'issue_content' => '帳簿の記入漏れ',
              'frequency' => 'middle',
            ],
          ],
        ],
        [
          'name' => '品質確保の実施',
          'judgement_base' => "品質確保の実施に関する記録を帳簿に保管しているか\n記入漏れはないか",
          'details' => [
            [
              'issue_content' => '品質確保の実施に関する帳簿がない',
              'frequency' => 'middle',
            ],
            [
              'issue_content' => '品質確保に関する帳簿の記入漏れ',
              'frequency' => 'middle',
            ],
          ],
        ],
        [
          'name' => '苦情処理、回収処理その他不良品の処理',
          'judgement_base' => "苦情処理、回収処理その他不良品の処理に関する記録を帳簿に保管しているか\n記入漏れはないか",
          'details' => [
            [
              'issue_content' => '処理記録に関する帳簿がない',
              'frequency' => 'middle',
            ],
            [
              'issue_content' => '苦情処理等に関する帳簿の記入漏れ',
              'frequency' => 'middle',
            ],
          ],
        ],
        [
          'name' => '従業者の教育訓練',
          'judgement_base' => "従業者の教育訓練に関する記録を帳簿に保管しているか\n記入漏れはないか",
          'details' => [
            [
              'issue_content' => '教育訓練実施に関する帳簿がない',
              'frequency' => 'middle',
            ],
            [
              'issue_content' => '教育訓練実施に関する帳簿の記入漏れ',
              'frequency' => 'middle',
            ],
          ],
        ],
      ]
    ],
  ];

  const FREQUENCIES_ARRAY = [
    // 防災, 食品...
    1 => [
      'wide' => [
        1 => [0, 0],
        2 => [0, 0],
        3 => [0, 0],
        4 => [0, 0],
        5 => [0, 1],
        6 => [1, 2],
        7 => [1, 4],
        8 => [2, 4],
        9 => [2, 5],
        10 => [2, 7],
        11 => [6, 12],
      ],
      'middle' => [
        1 => [0, 0],
        2 => [0, 0],
        3 => [0, 0],
        4 => [0, 0],
        5 => [0, 1],
        6 => [1, 1],
        7 => [1, 2],
        8 => [1, 2],
        9 => [0, 3],
        10 => [1, 4],
        11 => [3, 7],
      ],
      'narrow' => [
        1 => [0, 0],
        2 => [0, 0],
        3 => [0, 0],
        4 => [0, 0],
        5 => [0, 1],
        6 => [0, 1],
        7 => [0, 1],
        8 => [1, 1],
        9 => [1, 1],
        10 => [1, 2],
        11 => [1, 4]
      ],
    ],
    2 => [
      'wide' => [
        1 => [0, 0],
        2 => [0, 0],
        3 => [0, 0],
        4 => [0, 0],
        5 => [0, 1],
        6 => [0, 1],
        7 => [1, 1],
        8 => [1, 1],
        9 => [1, 2],
        10 => [1, 3],
      ],
      'middle' => [
        1 => [0, 0],
        2 => [0, 0],
        3 => [0, 0],
        4 => [0, 1],
        5 => [0, 1],
        6 => [0, 1],
        7 => [0, 2],
        8 => [1, 2],
      ],
      'narrow' => [
        1 => [0, 0],
        2 => [0, 0],
        3 => [0, 0],
        4 => [0, 0],
        5 => [0, 0],
        6 => [0, 1],
        7 => [0, 1],
        8 => [0, 1],
        9 => [1, 1],
      ]
    ],
    3 => [
      'middle' => [
        1 => [0, 0],
        2 => [0, 0],
        3 => [0, 0],
        4 => [0, 0],
        5 => [0, 1],
        6 => [0, 1],
        7 => [0, 1],
        8 => [0, 1],
        9 => [1, 1],
      ],
      'narrow' => [
        1 => [0, 0],
        2 => [0, 0],
        3 => [0, 0],
        4 => [0, 0],
        5 => [0, 1],
        6 => [0, 1],
        7 => [0, 1],
        8 => [0, 1],
      ]
    ]
  ];

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('exam_issue_details')->truncate();
    DB::table('exam_issues')->truncate();
    DB::table('exams')->truncate();

    foreach (self::EXAM_ARRAY as $exam) {
      // 検査種類[防災/食品...]
      $examRow = Exam::create([
        'exam_code' => $exam['exam_code'],
        'name' => $exam['name'],
        'risk_rank_id' => $exam['risk_rank_id'],
        'is_spot' => $exam['is_spot'],
        'interval' => $exam['interval'],
        'file_name' => $exam['file_name'],
        'icon_name' => $exam['icon_name'],
        'color' => $exam['color'],
      ]);

      if (array_key_exists('issues', $exam)) {
        // 検査別の設問
        foreach ($exam['issues'] as $issue) {
          $issueRow = ExamIssue::create([
            'exam_code' => $examRow->exam_code,
            'name' => $issue['name'],
            'judgement_base' => $issue['judgement_base'],
          ]);
          // 設問別の指摘内容
          foreach ($issue['details'] as $detail) {
            ExamIssueDetail::create([
              'exam_issue_id' => $issueRow->id,
              'issue_content' => $detail['issue_content']
            ]);
          }
        }
      }
    }
  }
}
