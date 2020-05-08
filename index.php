<?php

session_start();
if (empty($_SESSION)) {
    $_SESSION["燃料"] = 0;
    $_SESSION["弾薬"] = 0;
    $_SESSION["鋼材"] = 0;
    $_SESSION["ボーキ"] = 0;
    $_SESSION["現在燃料"] = 0;
    $_SESSION["現在弾薬"] = 0;
    $_SESSION["現在鋼材"] = 0;
    $_SESSION["現在ボーキ"] = 0;
    $_SESSION["任意"] = array();
    $_SESSION["感情"] = 0;
    $_SESSION["コイン"] = 0;
    $_SESSION["現在コイン"] = 0;
    $_SESSION["アイテム"] = 0;
    $_SESSION["装備"] = array();
}




if (!empty($_POST["btn_submit"])) {
    try {
        switch ($_POST["type"]) {
            case "燃料":
                $_SESSION["燃料"] += (int) $_POST["resource_value"];
                break;
            case "弾薬":
                $_SESSION["弾薬"] += (int) $_POST["resource_value"];
                break;
            case "鋼材":
                $_SESSION["鋼材"] += (int) $_POST["resource_value"];
                break;
            case "ボーキ":
                $_SESSION["ボーキ"] += (int) $_POST["resource_value"];
                break;
            case "任意":
                $_SESSION["任意"][] = (int) $_POST["resource_value"];
                break;
            case "感情":
                $_SESSION["感情"] += (int) $_POST["resource_value"];
                break;
            case "すべての資材":
                $_SESSION["燃料"] += 3;
                $_SESSION["弾薬"] += 3;
                $_SESSION["鋼材"] += 3;
                $_SESSION["ボーキ"] += 3;
                break;
            case "アイテム":
                $_SESSION["アイテム"]++;
                break;
            case "コイン":
                $_SESSION["コイン"]++;
                break;
            case "開発":
                $_SESSION["装備"][] = $_POST["development"];
        }
        header("Location: ./index.php");
    } catch (Exception $e) {
        echo "値が違います" . $e->getMessage();
    }
}

if (!empty($_POST["btn_submit_now"])) {
    try {
        $_SESSION["現在燃料"] += (int) $_POST["現在燃料"];
        $_SESSION["現在弾薬"] += (int) $_POST["現在弾薬"];
        $_SESSION["現在鋼材"] += (int) $_POST["現在鋼材"];
        $_SESSION["現在ボーキ"] += (int) $_POST["現在ボーキ"];
        $_SESSION["現在コイン"] += (int) $_POST["現在コイン"];
        header("Location: ./index.php");
    } catch (Exception $e) {
        echo "値が違います" . $e->getMessage();
    }
}



?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>戦果集計ツール</title>
    <link rel="stylesheet" href="./stylesheet.css">
</head>

<body>
    <script src="https://unpkg.com/clipboard@2/dist/clipboard.min.js"></script>
    <script>
        new ClipboardJS('.btn');
    </script>
    <div class="container">
        <h1>艦これRPG戦果集計ツール</h1>
        <br>
        <textarea id="outcome" readonly><?php echo "獲得した資材:" . $_SESSION["燃料"] . "/" . $_SESSION["弾薬"] . "/" . $_SESSION["鋼材"] . "/" . $_SESSION["ボーキ"] . " 任意:" . implode(",", $_SESSION["任意"]) . " 感情:" . $_SESSION["感情"] . " 家具コイン:" . $_SESSION["コイン"]  .
                                            "\n現在資材:" . (int) ($_SESSION["燃料"] +  $_SESSION["現在燃料"]) . "/" . (int) ($_SESSION["弾薬"] +  $_SESSION["現在弾薬"]) . "/" . (int) ($_SESSION["鋼材"] + $_SESSION["現在鋼材"]) . "/" . (int) ($_SESSION["ボーキ"] + $_SESSION["現在ボーキ"]) . " コイン:" . (int) ($_SESSION["コイン"] + $_SESSION["現在コイン"]) .
                                            "\nアイテム:" . $_SESSION["アイテム"] .
                                            "\n出た装備:" . implode(",", $_SESSION["装備"]) ?></textarea>
        <form method="post">
            <input type="submit" class="btn_submit normal_outcome" name="select_outcome" value="通常戦果">
            <input type="submit" class="btn_submit special_outcome" name="select_outcome" value="特殊戦果">
            <input type="submit" class="btn_submit now_resource" name="select_outcome" value="現在資材の入力">
            <input type="submit" class="btn_submit distribution" name="select_outcome" value="任意資材の振り分け">
            <button class="btn copy" data-clipboard-target="#outcome">クリップボードにコピー</button>
            <input type="submit" class="btn_submit reset" name="select_outcome" value="リセット">
        </form>
        <form method="post">
            <?php if (!empty($_POST["select_outcome"])) : ?>
                <?php if ($_POST["select_outcome"] == "通常戦果") : ?>
                    <select name="type">
                        <option value="燃料">燃料</option>
                        <option value="弾薬">弾薬</option>
                        <option value="鋼材">鋼材</option>
                        <option value="ボーキ">ボーキ</option>
                        <option value="任意">任意</option>
                        <option value="感情">感情</option>
                    </select>
                    <input class="input-num" type="text" name="resource_value">
                    <input class="btn_submit submit" type="submit" name="btn_submit">
                <?php elseif ($_POST["select_outcome"] == "特殊戦果") : ?>
                    <select name="type">
                        <option value="すべての資材">すべての資材+3</option>
                        <option value="アイテム">アイテム+1</option>
                        <option value="コイン">コイン+1</option>
                        <option value="開発">砲類開発</option>
                        <option value="開発">艦載機開発</option>
                        <option value="開発">新特殊開発</option>
                    </select>
                    <p>開発表なら出た装備を入力。他なら何も記入せずに送信。</p>
                    <input class="development-text" type="text" name="development">
                    <input class="btn_submit submit" type="submit" name="btn_submit">
                <?php elseif ($_POST["select_outcome"] == "現在資材の入力") : ?>
                    <div class="parent">
                        <ul class="input-now">
                            <li>
                                <p>燃料</p>
                                <input class="input-num" type="text" name="現在燃料">
                            </li>
                            <li>
                                <p>弾薬</p>
                                <input class="input-num" type="text" name="現在弾薬">
                            </li>
                            <li>
                                <p>鋼材</p>
                                <input class="input-num" type="text" name="現在鋼材">
                            </li>
                            <li>
                                <p>ボーキ</p>
                                <input class="input-num" type="text" name="現在ボーキ">
                            </li>
                            <li>
                                <p>コイン</p>
                                <input class="input-num" type="text" name="現在コイン">
                            </li>
                            <li>
                                <input class="btn_submit submit" type="submit" name="btn_submit_now">
                            </li>
                        </ul>
                    </div>
                <?php elseif ($_POST["select_outcome"] == "任意資材の振り分け") : ?>
                    <p>現在の任意資材</p>
                    <?php echo implode(",", $_SESSION["任意"]) ?>
                    <p>振り分ける資材を入力して送信</p>
                    <select name="free">
                        <?php foreach ($_SESSION["任意"] as $free) : ?>
                            <option value="<?php echo $free ?>"><?php echo $free ?></option>
                        <?php endforeach ?>
                    </select>
                    <input class="btn_submit submit" type="submit" name="btn_distribution">
                <?php elseif ($_POST["select_outcome"] == "リセット") : ?>
                    <?php
                    $_SESSION["燃料"] = 0;
                    $_SESSION["弾薬"] = 0;
                    $_SESSION["鋼材"] = 0;
                    $_SESSION["ボーキ"] = 0;
                    $_SESSION["現在燃料"] = 0;
                    $_SESSION["現在弾薬"] = 0;
                    $_SESSION["現在鋼材"] = 0;
                    $_SESSION["現在ボーキ"] = 0;
                    $_SESSION["任意"] = array();
                    $_SESSION["感情"] = 0;
                    $_SESSION["コイン"] = 0;
                    $_SESSION["現在コイン"] = 0;
                    $_SESSION["アイテム"] = 0;
                    $_SESSION["装備"] = array();
                    header("Location: ./index.php");
                    ?>
                <?php endif ?>
            <?php else : ?>
                <p>項目を選択してください</p>
            <?php endif ?>
        </form>
        <?php
        if (!empty($_POST["btn_distribution"])) {
            try {
                if (in_array((int) $_POST["free"], $_SESSION["任意"])) { ?>
                    <?php unset($_SESSION["任意"][array_search((int) $_POST["free"], $_SESSION["任意"])]) ?>
                    <p>どこに振り分けるか選択してください</p>
                    <form method="post">
                        <select name="type">
                            <option value="燃料">燃料</option>
                            <option value="弾薬">弾薬</option>
                            <option value="鋼材">鋼材</option>
                            <option value="ボーキ">ボーキ</option>
                        </select>
                        <input class="input-num" type="text" name="resource_value" value=<?php echo $_POST["free"] ?> readonly>
                        <input class="btn_submit submit" type="submit" name="btn_submit">
                    </form>
        <?php } else {
                    echo "値が違います";
                    echo $_POST["free"];
                }
            } catch (Exception $e) {
                echo "値が違います" . $e->getMessage();
            }
        }
        ?>
    </div>

</body>

</html>