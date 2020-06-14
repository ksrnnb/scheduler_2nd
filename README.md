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