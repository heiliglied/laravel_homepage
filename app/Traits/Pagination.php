<?php
namespace App\Traits;

trait Pagination
{
	/*
		$parameters = [
			'skip' => true or false,
			'currentPage' => current showing page number(integer),
			'pageSize' => show rows per page,
			'showPages' => show pagination size
			'totalCount' => total rows count
		]
	*/
	public function showPagination(array $parameters)
	{
		$totalPage = ceil($parameters['totalCount'] / $parameters['pageSize']);
		
		$pages = array();
		
		if($totalPage <= $parameters['showPages']) {
			for($i = 1; $i <= $totalPage; $i++)
			{
				$pages[] = $i;
			}
		} else {
			/* 
			현재 페이지보다 전체 페이지가 크더라도 여분을 제공하지 않는다.
			*/
			$endPage = $parameters['showPages'] >= $parameters['currentPage'] ? $parameters['showPages'] : $parameters['currentPage'];
			$endRoop = $endPage > $totalPage ? $totalPage : $endPage;
			$startPage = $endPage - $parameters['showPages'] + 1;
			$startRoop = $startPage < 1 ? 1 : $startPage;
			
			/*
			현재 페이지보다 전체 페이지가 클 경우 클릭으로 이동할 수 있도록 여분의 페이지를 제공한다.			
			$endPage = $parameters['showPages'] > $parameters['currentPage'] ? $parameters['showPages'] : $parameters['currentPage'] + 1;
			$endRoop = $endPage >= $totalPage ? $totalPage : $endPage;
			$startPage = $endRoop - $parameters['showPages'] + 1;
			$startRoop = $startPage < 1 ? 1 : $startPage;
			*/
			
			if($parameters['skip'] == false) {				
				for($i = $startRoop; $i <= $endRoop; $i++)
				{
					$pages[] = $i;
				}
			} else {
				//현재 페이지보다 전체 페이지가 클 경우 클릭으로 이동할 수 있도록 여분의 페이지를 제공한다.			
				$endPage = $parameters['showPages'] > $parameters['currentPage'] ? $parameters['showPages'] : $parameters['currentPage'] + 1;
				$endRoop = $endPage >= $totalPage ? $totalPage : $endPage;
				$startPage = $endRoop - $parameters['showPages'] + 1;
				$startRoop = $startPage < 1 ? 1 : $startPage;
				
				
				$roopCount = 1;
				for($i = $startRoop; $i <= $endRoop; $i++)
				{
					if($roopCount == 1 && $i != 1) {
						$pages[] = "prevSkip";
					} else if($roopCount == $parameters['showPages'] && $i < $totalPage){
						$pages[] = "nextSkip";
					} else {
						$pages[] = $i;
					}
					$roopCount++;
				}
			}
		}

		$result = [
			'totalPage' => $totalPage,
			'pages' => $pages,
		];
		
		return $result;
	}
}
?>