<?php 

require_once("PHPUnit/Extensions/AppiumTestCase.php");
require_once("PHPUnit/Extensions/AppiumTestCase/Element.php");


define("CAPS", array(
      array(
      	"local" => true,
        "port" => 4723,
        "browserName" => "",
        "desiredCapabilities" => array(
          "platformName" => "Android",
          "automationName" => "UIAutomator2",
          "deviceName" => "Android Emulator",
          "appActivity" => ".SplashScreen",
          "appPackage" => "org.owline.kasirpintar",
          "noReset" => true
        )
      )
    ));

class AppiumInteractions extends PHPUnit_Extensions_AppiumTestCase {

	public static $browsers = CAPS;
	public $package = 'org.owline.kasirpintar';

	public function testLogin() {
		$this->waitForElemBy('id', "id/select_language"); //tunggu sampe splash screen nya selesai
		$spinner = $this->element($this->createCriteria('id', "id/select_language"));

		$spinner->click(); //click dulu biar pilihannya muncul
		$options = $this->elements($this->createCriteria('id', 'id/linier')); //ambil semua opsi, id opsi nya linier
		assert($options);

		$selected = false;
		foreach($options as $opt) { //loop semua opsi
			$kode = $opt->element($this->createCriteria('id', 'id/kode'));

			if($kode->text() == "Indonesia") { //cari yang kodenya Indonesia
				$selected = true;
				$opt->click();
				break;
			}
		}

		assert($selected);
		$this->element($this->createCriteria('id', 'id/fab'))->click(); //click lanjut

		$this->waitForElemBy('id', 'id/error_email'); //tunggu sampe pindah halaman/activity
		$this->element($this->createCriteria('id', 'id/error_email'))->click();
		$this->keys('rimamtqna@gmail.com');

		$this->element($this->createCriteria('id', 'id/error_password'))->click();
		$this->keys('Kasirpintar18');

		$this->element($this->createCriteria('id', 'id/buttLogin'))->click();
		$this->element($this->createCriteria('id', 'id/ya'))->click();

		assert($this->element($this->createCriteria('id', 'id/gambar_profile'))); //klo berhasil gaada button login lagi
	}

	public function createCriteria($using, $tag) {
		$tag = $using == 'id'? "$this->package:$tag" : $tag;
		return $this->using($using)->value($tag);
	}

  	public function waitForElemsBy($using, $tag)
  	{
	    $element;
	    $i = 0;
	    while ($i < 20) {
	        $element = $this->elements($this->createCriteria($using, $tag));
	        if ($element) 
	        	break;

	        sleep(1);
	    }
	    return $element;
  	}

  	public function waitForElemBy($using, $tag)
  	{
	    $elems = $this->waitForElemsBy($using, $tag);
	    if ($elems) 
	    	return $elems[0];
  	}

}