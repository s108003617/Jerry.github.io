<?php
require __DIR__. '/parts/admin-required.php';
require __DIR__ . '/config/pdo-connect.php';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;

if (empty($sid)) {
  header('Location: list.php');
  exit; # 結束 php 程式, die()
}

$sql = "SELECT * FROM address_book WHERE sid=$sid";
$row = $pdo->query($sql)->fetch();

# 如果沒有這個編號的資料, 轉向回列表頁
if (empty($row)) {
  header('Location: list.php');
  exit; # 結束 php 程式, die()
}
/*
header('Content-Type: application/json');
echo json_encode($row);
exit;
*/
$title = '編輯通訊錄';

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
<style>
  .required {
    color: red;
  }

  .form-text {
    color: red;
  }
</style>
<div class="container">
  <div class="row">
    <div class="col-6">
      <div class="card">

        <div class="card-body">
          <h5 class="card-title">編輯資料</h5>
          <form name="form1" onsubmit="sendData(event)">
            <input type="hidden" name="sid" value="<?= $row['sid'] ?>">
            <div class="mb-3">
              <label for="name" class="form-label">編號</label>
              <input type="text" class="form-control" value="<?= $row['sid'] ?>" disabled>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label"><span class="required">**</span> 姓名</label>
              <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label"><span class="required">**</span> email</label>
              <input type="text" class="form-control" id="email" name="email" value="<?= $row['email'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="mobile" class="form-label">mobile</label>
              <input type="text" class="form-control" id="mobile" name="mobile" value="<?= $row['mobile'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="birthday" class="form-label">birthday</label>
              <input type="date" class="form-control" id="birthday" name="birthday" value="<?= $row['birthday'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">address</label>

              <textarea class="form-control" id="address" name="address" rows="3"><?= $row['address'] ?></textarea>
              <div class="form-text"></div>
            </div>


            <button type="submit" class="btn btn-primary">修改</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">資料修改結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料修改成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
        <a href="list.php" class="btn btn-primary">到列表頁</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel2">資料修改結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          資料沒有修改
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
        <a href="list.php" class="btn btn-primary">到列表頁</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  const nameField = document.form1.name;
  const emailField = document.form1.email;

  function validateEmail(email) {
    var re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  const sendData = e => {
    e.preventDefault();

    // 回復沒有提示的狀態
    nameField.style.border = '1px solid #CCCCCC';
    nameField.nextElementSibling.innerHTML = '';
    emailField.style.border = '1px solid #CCCCCC';
    emailField.nextElementSibling.innerHTML = '';

    let isPass = true; // 有沒有通過檢查

    // TODO: 要做欄位資料檢查
    if (nameField.value.length < 2) {
      isPass = false;
      // 跳提示用戶
      nameField.style.border = '1px solid red';
      nameField.nextElementSibling.innerHTML = '請填寫正確的姓名';
    }

    if (!validateEmail(emailField.value)) {
      isPass = false;
      emailField.style.border = '1px solid red';
      emailField.nextElementSibling.innerHTML = '請填寫正確的 Email';
    }


    // 如果欄位資料都有通過檢查
    if (isPass) {
      const fd = new FormData(document.form1); // 建立一個只有資料的表單物件

      fetch('edit-api.php', {
          method: 'POST',
          body: fd, // 預設的 Content-Type: multipart/form-data
        })
        .then(r => r.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            myModal.show();
          } else {
            myModal2.show();
          }
        }).catch(ex => {
          console.log(`fetch() 發生錯誤, 回傳的 JSON 格式是錯的`);
          console.log(ex);
        })
    }
  }

  const myModal = new bootstrap.Modal('#exampleModal');
  const myModal2 = new bootstrap.Modal('#exampleModal2');
</script>
<?php include __DIR__ . '/parts/html-foot.php' ?>