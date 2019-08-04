# subomdule

`laradock`をsubmoduleとして構成していますので、まずはその更新を行います。

```sh
git submodule init
git submodule update
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
