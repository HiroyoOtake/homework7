<?php
class TableBase
{
	public $data;
	public $dbh;
	public $tablename;

	const DSN = 'mysql:host=localhost;dbname=homework7_class;charset=utf8;';
	const USER = 'root';
	const PASSWORD = 'root';
	
	public function set($data)
	{
		$this->data = $data;
	}

	public function __construct()  
	{
		try {
			$this->dbh = new PDO(self::DSN, self::USER, self::PASSWORD);
				// echo '成功しました！';
		} 
		catch (PDOException $e) {
			echo $e->getMessage();
			exit;
	}
	}

	public function insert()
	{
		$keys = array_keys($this->data);
		// var_dump($data);
		$tablecolumn = implode(", ", $keys);
		// var_dump($tablecolumn);
		$tablevalue = ":" . implode(", :", $keys);

		$sql = "insert into members ($tablecolumn, created_at) values ($tablevalue, now())";
		$stmt = $this->dbh->prepare($sql);
		// var_dump($sql);
		
		foreach($this->data as $key => $value)
		{
		$stmt->bindValue(":$key", $value);
		}

		return  $stmt->execute();
		// print_r($stmt->errorInfo());
	}

	public function delete($id)
	{
		$sql = "delete from " . $this->tablename . " where id = :id";

		$stmt = $this->dbh->prepare($sql);

		$stmt->bindParam(":id", $id);

		return  $stmt->execute();
	}
}

class Member extends TableBase
{
	public $tablename = 'members';

	public function findByEmail($email)
	{
		$sql = "select * from members where email = :email";

		$stmt = $this->dbh->prepare($sql);

		$stmt->bindParam(":email", $email);

		$result = $stmt->execute();
		
	        $members = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($members) {
			return $members; 
		} else {
			return false;
		}
	}

}

class ShopItem extends TableBase
{
	public $tablename = 'shop_items';

	public function findByCode($code)
	{
		$sql = "select * from shop_items where code = :code";

		$stmt = $this->dbh->prepare($sql);

		$stmt->bindParam(":code", $code);

		$result = $stmt->execute();
		
	        $codes = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($codes) {
			return $codes; 
		} else {
			return false;
		}
	}

}

// members テーブルのデータを表します。 
$member = new Member();

// メンバーのデータをセットします。 
$member->set(array( 
'name' => 'テスト名', 
'age' => 30, 
'email' => 'test@example.com', 
));
// var_dump($member->data);
// $member->set() でセットしたデータを members テーブルに追加登録します。 
// この時 created_at カラムに現在日時を自動的にセットするようにしてください。 
// 登録が成功した場合は true 、失敗した場合は false を返します。 
$result = $member->insert();
var_dump($result);
echo "<br>";

// 引数で指定されたメールアドレスのユーザーを members テーブルから探し、 
// もし見つかった場合、そのデータを以下の形式で返します。 
// array( 
// 'id' => 'members テーブル の id カラムの値', 
// 'name' => 'members テーブル の name カラムの値', 
// 'age' => 'members テーブル の age カラムの値', 
// 'email' => 'members テーブル の email カラムの値', 
// 'created_at' => 'members テーブル の created_at カラムの値', 
// ); 
// // ユーザーが見つからなかった場合、false を返します。 
$data = $member->findByEmail('test@example.com');
var_dump($data);
echo "<br>";

// // 引数で指定された id を持つ members テーブルのレコードを削除します。 
// // 削除が成功した場合は true 、失敗した場合は false を返します。 
$result = $member->delete($data['id']);
var_dump($result);
echo "<br>";

// ここでは false が返ってくるはずです。 
$data = $member->findByEmail('test@example.com'); 
var_dump($data);
echo "<br>";

//応用要件
$shopItem = new ShopItem(); 
// $shopItem->set($data); 
// $shopItem->set(array( 
// 'name' => 'テスト名', 
// 'code' => 30, 
// 'price' => 100, 
// ));
// $shopItem->insert(); 
// $shopItem->delete($id); 
// $shopItem->findByCode($code);
?>
