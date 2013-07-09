<?php

class NotificationController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Notification;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Notification']))
		{
			$model->attributes=$_POST['Notification'];
			if($model->save()){
				$message = $_POST['Notification']['notification_body'];
				$devices = UserDevice::model()->findAll('status = 1');
				self::devicecheck();
				foreach($devices as $dev){
					self::push($dev->device_token,$message);
				}
				$this->redirect(array('view','id'=>$model->notification_id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Sending Push notification
	 * /
	 */
	 
	 function push($token,$msg){
				 // Put your device token here (without spaces):
		$deviceToken = $token;

		// Put your private key's passphrase here:
		$passphrase = 'phyder@thewall';

		// Put your alert message here:
		//$message = '{sts:200,msg:"'.$msg.'"}';
		$message = $msg;
		////////////////////////////////////////////////////////////////////////////////
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', dirname(__FILE__).'/TheWall.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
		$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	 	
			
		if($fp === false) {
    			apns_close_connection($fp);
		    	error_log ("APNS Feedback Request Error: $error_code - $error_message", 0);
			}
		/*if (!$fp)
			exit("Failed to connect: $err $errstr");
		else 
			echo 'Connected to APNS';
		*/
		// Create the payload body
		$body['aps'] = array(
			'alert' => $message,
			'sound' => 'chime',
			'badge' => 1
			);

		// Encode the payload as JSON
		$payload = json_encode($body);
		//die(var_dump($payload));
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));

		/*if (!$result)
			echo 'Message not delivered' . PHP_EOL;
		else
			echo 'Message successfully delivered' . PHP_EOL;

		// Close the connection to the server
		fclose($fp);
			*/	 
		 
	 }
	 
	 
	 /**
	  * Function to check if the device uninstall the app or not 
	  * Basically this is feedback from apple
	  */
	 
	 function devicecheck(){
	 		$cert_file = dirname(__FILE__).'/TheWall.pem';
			$cert_pw = 'phyder@thewall';
		
			$stream_context = stream_context_create();
			stream_context_set_option($stream_context, 'ssl', 'local_cert', $cert_file);
			if (strlen($cert_pw))
    			stream_context_set_option($stream_context, 'ssl', 'passphrase', $cert_pw);

			$apns_connection = stream_socket_client('ssl://feedback.sandbox.push.apple.com:2196', 
			$error_code, $error_message, 60, STREAM_CLIENT_CONNECT, $stream_context);

			if($apns_connection === false) {
    			apns_close_connection($apns_connection);
		    	error_log ("APNS Feedback Request Error: $error_code - $error_message", 0);
			}

			$feedback_tokens = array();

			while(!feof($apns_connection)) {
    		$data = fread($apns_connection, 38);
    		if(strlen($data)) {
        			$feedback_tokens[] = unpack("N1timestamp/n1length/H*devtoken", $data);
    			}
			}
			fclose($apns_connection);
			$test = array();
			if (count($feedback_tokens))
	    		foreach ($feedback_tokens as $k => $token) {
	         	 	   $test[] = $token;
	    		}
	    	if(sizeof($test))
	    		die(var_dump($test));
	 	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Notification']))
		{
			$model->attributes=$_POST['Notification'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->notification_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Notification');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Notification('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Notification']))
			$model->attributes=$_GET['Notification'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Notification the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Notification::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Notification $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='notification-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
