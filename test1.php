<?php
	function get_percentage($total, $number)
	{
		if ($total > 0 )
		{
			return round($number / ($total / 100),2);
		}

		else
		{
			return 0;
		}
	}


echo get_percentage(100,50).'%';
echo "<br />";
echo get_percentage(3,2).'%';
echo "<br />";

$firstNumber=100;
$secondNumber=2;
echo get_percentage($firstNumber,$secondNumber);
echo "<br />";
if (get_percentage($firstNumber,$secondNumber) > 50)
{
	echo "Greater than 50%";
}
else
{
	echo "Less than 50%";
}
?>
