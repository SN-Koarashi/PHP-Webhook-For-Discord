<?php
/**
 * Discord Webhook API for embeds by PHP
 * @author 5026
 */
class DiscordWebhook{
	/**
	 * Webhook的完整網址
	 * @var string
	 */
	public $webhookURL;

	/**
	 * 訊息內容
	 * @var string
	 */
	public $content = "";

	/**
	 * Webhook顯示的名稱
	 * @var string
	 */
	public $username;

	/**
	 * Webhook頭像
	 * @var string
	 */
	public $avatar_url;
	
	/**
	 * 嵌入內容的頭像
	 * @var string
	 */
	public $avatar_embed_url;
	
	/**
	 * 嵌入內容底部文字
	 * @var string
	 */
	public $footer;

	/**
	 * 標題
	 * 嵌入內容的標題
	 * @var string
	 */
	public $title = "";

	/**
	 * 說明
	 * 嵌入內容的文字敘述
	 * @var string
	 */
	public $description = "";

	/**
	 * 網址
	 * 嵌入內容的標題網址
	 * @var string
	 */
	public $url = "";

	/**
	 * 時間戳
	 * 用來表示嵌入內容的時間，須符合 ISO8601 標準
	 * @var integer
	 */
	public $timestamp;

	/**
	 * 嵌入內容的側邊顏色
	 * @var string
	 */
	public $color;

	/**
	 * 嵌入內容的圖像網址(下方)
	 * @var string
	 */
	public $image = "";

	/**
	 * 嵌入內容的縮圖網址(右方)
	 * @var string
	 */
	public $thumbnail = "";

	/**
	 * 表示這個嵌入內容的作者
	 * @var string
	 */
	public $author = "";

	/**
	 * 嵌入內容的作者頭像網址
	 * @var string
	 */
	public $authorURL = "";
	
	/**
	 * 嵌入內容的頁腳頭像網址
	 * @var string
	 */
	public $icon_url = "";

	/**
	 * 嵌入內容的內容區段
	 * @var array
	 */
	public $fields;
	
	/**
	 * 透過訊息ID取得先前的訊息內容
	 * @return json
	 */
	public function selectMessage($msgID){
		$url = $this->webhookURL."/messages/".$msgID;

		$ch = curl_init();

		curl_setopt_array( $ch, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/json"
			]
		]);

		$response = curl_exec( $ch );
		curl_close( $ch );
		
		return $response;
	}
	
	/**
	 * 透過訊息ID更新先前的訊息內容
	 * @return json
	 */
	public function updateMessage($msgID, $embeds){
		$url = $this->webhookURL."/messages/".$msgID;
		$wh = json_decode($this->selectMessage($msgID), true);
		if($wh['code']){
			return json_encode(array('status'=>400,'reason'=>$wh['message']));
		}
		else{
			$ch = curl_init();
			curl_setopt_array( $ch, [
				CURLOPT_URL => $url,
				CURLOPT_CUSTOMREQUEST => 'PATCH',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $embeds,
				CURLOPT_HTTPHEADER => [
					"Content-Type: application/json"
				]
			]);

			curl_exec( $ch );
			curl_close( $ch );
			
			return json_encode(array('status'=>200,'reason'=>"OK"));
		}
	}
	
	/**
	 * 透過訊息ID刪除先前的訊息內容
	 * @return void
	 */
	public function deleteMessage($msgID){
		$url = $this->webhookURL."/messages/".$msgID;
		$wh = json_decode($this->selectMessage($msgID), true);
		if($wh['code']){
			return json_encode(array('status'=>400,'reason'=>$wh['message']));
		}
		else{
			$ch = curl_init();
			curl_setopt_array( $ch, [
				CURLOPT_URL => $url,
				CURLOPT_CUSTOMREQUEST => 'DELETE',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => [
					"Content-Type: application/json"
				]
			]);

			curl_exec( $ch );
			curl_close( $ch );
			
			return json_encode(array('status'=>200,'reason'=>"OK"));
		}
	}
	
	/**
	 * 輸出並傳送到 Discord API
	 * @return void
	 */
	public function sendMessage($embeds){
		$url = $this->webhookURL;

		$ch = curl_init();

		curl_setopt_array( $ch, [
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $embeds,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/json"
			]
		]);

		$response = curl_exec( $ch );
		curl_close( $ch );
		
		return $response;
	}
	

	/**
	 * 輸出嵌入內容
	 * @return json
	 */
	public function getEmbeds(){
		$hookObject = json_encode([
			"content" => $this->content,

			"username" => $this->username,

			"avatar_url" => $this->avatar_url, 
			
			"tts" => false,
			
			/*
			 * 嵌入內容
			 * 可以有好幾個，但這裡只寫入一個
			 * 所有嵌入內容以陣列串接
			 */
			"embeds" => [
				[
					"title" => $this->title,

					"type" => "rich",

					"description" => $this->description,

					"url" => $this->url,

					"timestamp" => ($this->timestamp)?$this->timestamp:date('Y-m-d\TH:i:s', time()),//"2018-03-10T19:15:45-05:00",

					"color" => ($this->color)?$this->color:hexdec("FFFFFF"),

					"footer" => [
						"text" => $this->footer,
						"icon_url" => $this->icon_url
					],

					"image" => [
						"url" => $this->image
					],

					"thumbnail" => [
						"url" => $this->thumbnail
					],
			
					"author" => [
						"name" => $this->author,
						"url" => $this->authorURL,
						"icon_url" => $this->avatar_embed_url,
					],

					"fields" => $this->fields
				]
			]

		], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		
		return $hookObject;
	}
}