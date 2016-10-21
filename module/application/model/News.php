<?php 

class News{
	public function getData($start,$count){
		$data=$this->getDataLang();

		$length=count($data);

		$result=array();

		$end=($start+$count)>$length?$length:$start+$count;

		for($i=$start;$i<$end;$i++){
			array_push($result, $data[$i]);
		}

		return $result;
	}

	private function getDataLang(){
		if($GLOBALS['lang']=='vi'){
			return array(
				array(
					'id'=>1,
					'title'=>'1. tin tức 1',
					'url'=>'tin-tuc-1'
				),
				array(
					'id'=>2,
					'title'=>'2. tin tức 2',
					'url'=>'tin-tuc-2'
				),
				array(
					'id'=>3,
					'title'=>'3. tin tức 3',
					'url'=>'tin-tuc-3'
				),
				array(
					'id'=>4,
					'title'=>'4. tin tức 4',
					'url'=>'tin-tuc-4'
				),
				array(
					'id'=>5,
					'title'=>'5. tin tức 5',
					'url'=>'tin-tuc-5'
				),
				array(
					'id'=>6,
					'title'=>'6. tin tức 6',
					'url'=>'tin-tuc-6'
				)
			);
		}

		return array(
				array(
					'id'=>1,
					'title'=>'1. news 1',
					'url'=>'tin-tuc-1'
				),
				array(
					'id'=>2,
					'title'=>'2. news 2',
					'url'=>'tin-tuc-2'
				),
				array(
					'id'=>3,
					'title'=>'3. news 3',
					'url'=>'tin-tuc-3'
				),
				array(
					'id'=>4,
					'title'=>'4. news 4',
					'url'=>'tin-tuc-4'
				),
				array(
					'id'=>5,
					'title'=>'5. news 5',
					'url'=>'tin-tuc-5'
				),
				array(
					'id'=>6,
					'title'=>'6. news 6',
					'url'=>'tin-tuc-6'
				)
			);
	}
}