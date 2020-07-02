## LaravelでReactを使う
以下のコマンドを実行する。基本的に[ドキュメント](https://readouble.com/laravel/7.x/ja/frontend.html#using-react)を参照。
```bash
composer require laravel/ui
php artisan ui react
php artisan ui react --auth
```
今回は`--auth`のほうでエラーが出た。ひとまず無視。あと以下のようなメッセージが出てくる。
```bash
Please run "npm install && npm run dev" to compile your fresh scaffolding.
```
そのまま実行する。
```bash
npm install && npm run dev
```

あとは、bladeテンプレート内で、`app.js`を読み込む。
```html
<script src="{{asset('js/app.js')}}"></script>
```
`app.js`内の`require('./components/Example');`を適当に使いたいファイルの名前に変える。
```javascript
require('./components/App');
```
そのファイルの中で、Reactを書いていく。
```javascript
import React from 'react';
import ReactDOM from 'react-dom';

class Test extends React.Component {
  render() {
    return (
      <p>Hello!</p>
    );
  }
}

ReactDOM.render(
  <Test />,
  document.getElementById('root')
);
```

以下のコマンドを実行すると、Reactがコンパイル（？）される。
`npm run コマンド名`で、`package.json`の中の`scripts`に書かれているコマンドが実行される。
```bash
npm run watch
```

## 複数のコンポーネントファイルをつくりたいとき
以下のように、`if`文で、そのidの要素があるかどうか調べる。要素がない場合に`ReactDOM.render()`を実行すると、エラーが発生する。
```javascript
if (document.getElementById('index')) {
  ReactDOM.render(
    <Schedule />,
    document.getElementById('index')
  );
}
```
app.js内は、使いたいコンポーネントを`require`する。
#### app.js
```javascript
require('./components/Add');
require('./components/Index');
```

## LaravelからReactに値を渡したい
JSONでの受け渡しとか調べたけどよくわからん。今回は`input`タグに入れておいて、取得するようにした。

## migrateできない
`php artisan migrate`しようとすると、以下のようなエラーが発生。MySQLのrootユーザーにパスワードが設定されていないため。
```bash
Access denied for user 'root'@'localhost' (using password: NO) (SQL: select * from information_schema.tables where table_schema = scheduler and table_name = migrations and table_type = 'BASE TABLE’)
```

パスワードを設定する方法は、[ドキュメント](https://dev.mysql.com/doc/refman/5.6/ja/assigning-passwords.html)を参照。
`PASSWORD()`はハッシュ化してから、userテーブルに保存する。passwordは適当にいいやつに変える。その後、.envファイルの`DB_PASSWORD=`に、指定したパスワードを入れればOK
```sql
mysql> SET PASSWORD FOR
    -> 'root'@'localhost' = PASSWORD('password');
```

## Factoryを使用
```php
$users = factory(App\User::class, 3)->create();
```
で、3つできる。複数モデルの生成に`create`を使用する場合、返すのはインスタンスのコレクションなので、eachでそれぞれに対するコールバックがかける。
```php
$users = factory(App\User::class, 3)
           ->create()
           ->each(function ($u) {
                $u->posts()->save(factory(App\Post::class)->make());
            });
```

上の例の`posts()`は`hasMany()`でリレーションづけたやつ。違うページに載ってた。
[ドキュメント](https://readouble.com/laravel/7.x/ja/eloquent-relationships.html)
```php
public function posts()
{
    return $this->hasMany('App\Post');
}
```

[save](https://readouble.com/laravel/7.x/ja/eloquent-relationships.html#the-save-method)はそのモデルを保存。


[create](https://readouble.com/laravel/7.x/ja/database-testing.html#using-factories)は作成してsaveメソッドも実行する（データベースに保存される。）
引数がある場合は、その属性をオーバーライドする。引数に、普通の配列も指定できる。


makeはインスタンスの生成。保存はされないっぽい。

## hasManyのときの取り出し方
[ドキュメント](https://readouble.com/laravel/7.x/ja/eloquent-relationships.html#one-to-many)を参照。
`hasMany`の場合は、プロパティでモデルのコレクションが得られることに注意。
以下の例で`App\Post::find(1)->comments()`とすると、オブジェクトが返ってきて、`foreach`で各要素が取り出せない。
```php
$comments = App\Post::find(1)->comments;

foreach ($comments as $comment) {
    //
}
```

