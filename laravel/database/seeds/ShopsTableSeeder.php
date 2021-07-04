<?php

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Shop;
use App\Models\ExaminatorTeam;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Storage;
class ShopsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(FileUploadService $fileUploadService)
  {
    $shopList = [
      Branch::BRANCH_CODE_OKINAWA => [
        [
          'store_code' => 1,
          'name' => '那覇店',
          'file_name' => 'naha.jpg',
          'team_code' => 1,
        ],
        [
          'store_code' => 2,
          'name' => '国際通り店',
          'file_name' => 'kokusaidori.jpg',
          'team_code' => 1,
        ],
        [
          'store_code' => 3,
          'name' => '名護店',
          'file_name' => 'nago.jpg',
          'team_code' => 1,
        ],
      ],
      Branch::BRANCH_CODE_KYUSHU => [
        [
          'store_code' => 4,
          'name' =>  '博多店',
          'file_name' => 'hakata.jpg',
          'team_code' => 2,

        ],
        [
          'store_code' => 5,
          'name' =>  '長崎店',
          'file_name' => 'nagasaki.jpg',
          'team_code' => 2,
        ],
        [
          'store_code' => 6,
          'name' =>  '鹿児島店',
          'file_name' => 'kagoshima.jpg',
          'team_code' => 2,
        ],
        [
          'store_code' => 7,
          'name' =>  '宮崎店',
          'file_name' => 'miyazaki.jpg',
          'team_code' => 2,
        ],
      ],
      Branch::BRANCH_CODE_CHUSHIKOKU => [
        [
          'store_code' => 8,
          'name' =>  '広島店',
          'file_name' => 'hiroshima.jpg',
          'team_code' => 3,
        ],
        [
          'store_code' => 9,
          'name' =>  '岡山店',
          'file_name' => 'okayama.jpg',
          'team_code' => 3,
        ],
        [
          'store_code' => 10,
          'name' =>  '鳥取店',
          'file_name' => 'tottori.jpg',
          'team_code' => 3,
        ],
        [
          'store_code' => 11,
          'name' =>  '香川店',
          'file_name' => 'kagawa.jpg',
          'team_code' => 3,
        ],
      ],
      Branch::BRANCH_CODE_KANSAI => [
        [
          'store_code' => 12,
          'name' => '祇園店',
          'file_name' => 'gion.jpg',
          'team_code' => 4,
        ],
        [
          'store_code' => 13,
          'name' => '梅田店',
          'file_name' => 'umeda.jpg',
          'team_code' => 4,
        ],
        [
          'store_code' => 14,
          'name' => '伊丹店',
          'file_name' => 'itami.jpg',
          'team_code' => 4,
        ],
        [
          'store_code' => 15,
          'name' => 'なんば店',
          'file_name' => 'nanba.jpg',
          'team_code' => 4,
        ],
        [
          'store_code' => 16,
          'name' => '岸和田店',
          'file_name' => 'kishiwada.jpg',
          'team_code' => 4,
        ],
      ],
      Branch::BRANCH_CODE_TOKAI => [
        [
          'store_code' => 17,
          'name' => '名古屋店',
          'file_name' => 'nagoya.jpg',
          'team_code' => 5,
        ],
        [
          'store_code' => 18,
          'name' => '栄店',
          'file_name' => 'sakae.jpg',
          'team_code' => 5,
        ],
        [
          'store_code' => 19,
          'name' => '豊田店',
          'file_name' => 'toyota.jpg',
          'team_code' => 5,
        ],
      ],
      Branch::BRANCH_CODE_HOKURIKU => [
        [
          'store_code' => 20,
          'name' => '金沢店',
          'file_name' => 'kanazawa.jpg',
          'team_code' => 6,
        ],
        [
          'store_code' => 21,
          'name' => '富山店',
          'file_name' => 'toyama.jpg',
          'team_code' => 6,
        ],
        [
          'store_code' => 22,
          'name' => '福井店',
          'file_name' => 'fukui.jpg',
          'team_code' => 6,
        ],
      ],
      Branch::BRANCH_CODE_KITAKANTO => [
        [
          'store_code' => 23,
          'name' => '栃木店',
          'file_name' => 'tochigi.jpg',
          'team_code' => 8,
        ],
        [
          'store_code' => 24,
          'name' => '群馬店',
          'file_name' => 'gunma.jpg',
          'team_code' => 8,
        ],
        [
          'store_code' => 25,
          'name' => '茨城店',
          'file_name' => 'ibaraki.jpg',
          'team_code' => 8,
        ],
      ],
      Branch::BRANCH_CODE_MINAMIKANTO => [
        [
          'store_code' => 26,
          'name' => '新宿店',
          'file_name' => 'shinjuku.jpg',
          'team_code' => 7,
        ],
        [
          'store_code' => 27,
          'name' => '渋谷店',
          'file_name' => 'shibuya.jpg',
          'team_code' => 7,
        ],
        [
          'store_code' => 28,
          'name' => '横浜店',
          'file_name' => 'yokohama.jpg',
          'team_code' => 7,
        ],
        [
          'store_code' => 29,
          'name' => 'さいたま店',
          'file_name' => 'saitama.jpg',
          'team_code' => 7,
        ],
      ],
      Branch::BRANCH_CODE_TOHOKU => [
        [
          'store_code' => 30,
          'name' => '仙台店',
          'file_name' => 'sendai.jpg',
          'team_code' => 9,
        ],
        [
          'store_code' => 31,
          'name' => '山形店',
          'file_name' => 'yamagata.jpg',
          'team_code' => 9,
        ],
        [
          'store_code' => 32,
          'name' => '秋田店',
          'file_name' => 'akita.jpg',
          'team_code' => 9,
        ],
      ],
      Branch::BRANCH_CODE_HOKKAIDO => [
        [
          'store_code' => 33,
          'name' => '札幌店',
          'file_name' => 'sapporo.jpg',
          'team_code' => 10,
        ],
        [
          'store_code' => 34,
          'name' => ' 函館店',
          'file_name' => 'hakodate.jpg',
          'team_code' => 10,
        ],
        [
          'store_code' => 35,
          'name' => '小樽店',
          'file_name' => 'otaru.jpg',
          'team_code' => 10,
        ],
      ]
    ];
//    Shop::all()->pluck('file_name')->each(function($putPath) use ($fileUploadService){
//      if ($putPath !== null) {
//        $fileUploadService->delete($putPath);
//      }
//    });

    DB::table('shops')->truncate();
    collect($shopList)->each(function($shopArr, $branchCode) use ($fileUploadService){
      collect($shopArr)->each(function($shop) use ($branchCode, $fileUploadService){
        // DBへの追加
        $newShop = factory(Shop::class)->create([
          'branch_code' => $branchCode,
          'team_code' => $shop['team_code'],
          'store_code' => $shop['store_code'],
          'name' => $shop['name'],
          'file_name' => "shops/{$shop['store_code']}/{$shop['file_name']}",
        ]);
        // storage/app/public/shopディレクトリにfile_nameの画像データがあればS3へ保存 && 画像パスの更新
//        if (file_exists(storage_path('app/public/shops/' . $shop['file_name']))) {
//          $putData['putDir'] = 'shops/' . $newShop->store_code;
//          $putData['fileData'] = storage_path('app/public/shops/' . $shop['file_name']);
//          $putData['fileName'] = $shop['file_name'];
//          $fileUploadService->upload($putData);
//          $newShop->fill(['file_name' => "{$putData['putDir']}/{$putData['fileName']}"])->save();
//          $replaced = str_replace('.jpg', '_split.jpg', $shop['file_name']);
//          $putData['fileData'] = storage_path('app/public/shops/' . $replaced);
//          $putData['fileName'] = $replaced;
//          $fileUploadService->upload($putData);
//        } else {
//          $newShop->fill(['file_name' => null])->save();
//        }
      });
    });

  }
}
