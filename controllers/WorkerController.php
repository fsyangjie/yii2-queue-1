<?php
/**
 * 
 */
namespace mithun\queue\controllers;

use Yii;
use yii\console\Exception;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 * Manage Worker Job
 *
 * @author Mithun Mandal <mithun12000@gmail.com>
 */
class WorkerController extends BaseQueueController
{
	/**
	 * @var string
	 */
	public $templateFile = '@mithun/queue/worker/template.php';
	/**
	 * @var string the default command action.
	 */
	public $defaultAction = 'worker';
	
	
	/**
	 * Run worker to consume job.
	 * For example,
	 *
	 * ~~~
	 * yii queue/worker     			# List all workers
	 * yii queue/worker worker1 3   	#run worker name producer1 with max 3 process
	 * yii queue/worker worker1 3 2  	#run worker name producer1 with max 3 process with min 2 process
	 * ~~~
	 * @param string $producer the producer class
	 * @param integer $max the maxumum number of producer process
	 * @param integer $min the minimum number of producer process
	 * @return boolean the status of the action execution. 0 means normal, other values mean abnormal.
	 */
	public function actionWorker($worker = '', $max=1, $min=1)
	{
		if($worker){
			return $this->runWorker($worker);
		}else {
			$workerAr = $this->getWorkers();
			if (empty($workerAr)) {
				$this->stdout("No producer found.\n", Console::FG_GREEN);
				return self::EXIT_CODE_NORMAL;
			}
			$total = count($workerAr);
			
			foreach ($workerAr as $className) {
				$this->stdout("\t$className\n");
			}
			$this->stdout("\n");
			return 0;
		}
	}
	
	/**
	 * Creates a new Worker
	 *
	 * This command creates a new worker using the available worker template.
	 * After using this command, developers should modify the created worker
	 * skeleton by filling up the actual worker logic.
	 *
	 * @param string $name the name of the new worker. This should only contain
	 * letters, digits and/or underscores.
	 * @param string $path the path for generating new worker.
	 * This will be path alias (e.g. "@app") default "@app"
	 * @throws Exception if the name argument is invalid.
	 */
	public function actionCreate($name,$path = '@app')
	{
		$this->createPubsub($name,'Worker',$path.'/workers');
	}
}