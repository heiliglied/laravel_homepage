<?php
namespace App\Traits;

trait Settings
{
	public function getSettings()
	{
		return parse_ini_file(config_path('settings.ini'));
	}
	
	public function setSettings(array $datas, String $title = 'setting')
	{
		try {
			$text = "[" . $title . "]\n";
			foreach($datas as $key => $value) {
				$text .= $key . "=" . $value . "\n";
			}
			
			$fp = fopen(config_path('settings.ini'), 'w');
			fwrite($fp, $text);
			fclose($fp);
			
			return 'success';
		} catch(\Exception $e) {
			print_R($e->getMessage());
			return 'fail';
		}
	}
}