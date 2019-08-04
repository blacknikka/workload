# subomdule

`laradock`をsubmoduleとして構成していますので、まずはその更新を行います。

```sh
git submodule init
git submodule update
cd ./laradock
git checkout a2c7b46
```

# 初期化用のshellを実行

プロジェクトの初期化を行います。  
プロジェクトのルートディレクトリで実行する必要があります。

```sh
bash ./etc/init-project.sh
```

# サービスをUPする

```sh
sh dockerup.sh
```

# 利用する

`http://localhost/`で利用可能です。  

ログインにはデフォルトで以下のユーザとパスワードが使えます。

ユーザー名：`test1@example.com`  
パスワード：`test1`  
