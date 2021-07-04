* ACMの証明書リクエスト回数が20回を超えて制限がかかったので中断中

## 対応済みアクション
* "./host"ディレクトリでのホスト作成とNSレコードのお名前ドットコムへの設定
* "./cdn"ディレクトリでのCloudFront(SSL証明書も)とS3

## 制限が解除されたら
* main/main.tfをapply
* 手動でECSのクラスタ作成〜サービスの作成まで行う
* 手動でターゲットグループのターゲットに、EC2インスタンスを登録する
* もしかしたらmain.tfのdnsモジュール内に指定したdns_name"変数の設定が間違ってる(module.alb.dns_nameじゃない)かも。これはAレコードのエイリアスにALBのdns_nameを指定しているのだが、applyの結果、エイリアス名が下記のような形式になっていなかったので問題あり  

```bash
dualstack.XXXXX-alb-XXXXX.ap-northeast-1.elb.amazonaws.com.
```