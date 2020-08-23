# Scheduler
https://scheduler2nd.herokuapp.com/

## 以下、メモ

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
## 主キーと外部キー
Eloquentには主キーや外部キーに暗黙のルールがある。
リレーションを使うとき（hasOneとか）、特に指定しない場合は、`_id`をつけた名前を外部キーとする。また、親カラムの主キーは`id`になる。これをオーバーライドしたい場合は、以下のように指定する。
```php
return $this->hasOne('App\Phone', 'foreign_key', 'local_key');
```

## timestamps
特に指定しない場合は、`timestamps`が自動で生成されるので、モデルで無効にしてやる必要がある。
```php
public $timestamps = false;
```

## Pivot
中間テーブルを使用する場合はPivotを使うらしい。
Modelの代わりにPivotを使用すると、テーブル名が自動で割り当てられるので、指定する必要がある。
```php
protected $tables = 'availabilities';
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

## コントローラでモデルを使用するとき
ふつうに`App\Model名::where`とかやろうとすると、`App\Http\Controllers\App\モデル名`のクラスがみつからない、というエラーが発生する。
名前空間が`namespace App\Http\Controllers;`と指定されているため。

コントローラでモデルを使用するときは、`use App\モデル名`として、`モデル名::where`のように使う。

## bootstrapでテーブルのtd, thがCenterにこない
`npm run watch`するたびにapp.cssが更新されるから、app.cssを編集しても無駄。
`<table>`タグのクラスに`text-center`つければCenterにくるようになった。
あとは、`<th>`や`<td>`の子要素に`<div>`を作って`text-align: center`という方法も。。。

## bind
正直よくわかってない。。。bindしないとスコープが内部に入っちゃうとthisの対象が変わる？
```javascript

```

## cache
毎回、キャッシュ削除するのがめんどい。`<meta>`でキャッシュしないように設定したら、やらなくて済むようになった。
[リンク](https://stackoverflow.com/questions/51207570/how-to-clear-browser-cache-in-reactjs)を参照。

## stateの配列の一部を更新したい
`slice()`で配列つくって、一部を変更。
そのあと`setState()`で更新する。
```javascript
handleClick(rowIndex, symbolIndex) {
    const availabilities = this.state.availabilities.slice();
    availabilities[rowIndex] = symbolIndex;
    this.setState({
      availabilities: availabilities,
    });
  }
```

## array_filterの使い方
`array_filter`で条件を満たす配列を返す。以下のように、第三引数に`ARRAY_FILTER_USE_BOTH`を指定すると、キーと値を利用できる。
以下の`$form`は配列。
```php
$candidatesArray = array_filter($form, function($value, $key) {
  return (is_int($key));
  }, ARRAY_FILTER_USE_BOTH);
```

## よくわからんエラー
"Add [scheduleId] to fillable property to allow mass assignment on [モデル]."

対策としては以下を記述。なんで急にこれが必要になったのかが理解できない。。。
```php
protected $guarded = [];
```


## stateを関数の中で扱いたい
以下のように書くと、`handleClick`の中でのthisは`handleClick`自身となってしまう。
かといって、アロー関数で書こうとすると、エラーがでてしまう。→`render()`内で`bind`してやることで解決した。
```javascript
class Schedule extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      list: []
    };
  }

  handleClick(e) {
    const list = this.state.list;
  }
```

## フォーム送信後の改行文字
phpでtextareaで改行を含んだ文字を送った時、改行コードは`\r\n`となることに注意。
そのため、`\n`で`explode`しても、文字列の最後に`\r`が含まれてしまって、想定どおりに動かなくなった。念のため`trim`しとくほうがいい？