<html>
<head>
<title>phpNSLookup</title>
<!-- ---------------------------------------------------------- -->
<!-- Written by VE3OY@VE3OY.COM - MAY 2018                      -->
<!-- GPL licensing - Free to use as long as you leave THIS info -->
<!-- ---------------------------------------------------------- -->
<style>
body {
	color: #FFF;
	background: #000;
}
table {
	margin: 0 auto;
	min-width: 600px;
	max-width: 600px;
	background: #5080B0;
	box-shadow: 0px 0px 5px 5px #0AF;
}
tr {
	margin: 5px;
}
td {
	padding-left: 10px;
	padding-right: 10px;
}
button {
	border: 1px solid #000;
	box-shadow: 0px 0px 5px 5px #0AF;
}
#container {
	max-width: 600px; 
	margin: 0 auto; 
	color: #FFF;
	background: #5080B0;
	border: 5px ridge #FFF;
}
</style>
</head>
<body>
<?php
/* ---------------------------------------------------------- */
/* Written by VE3OY@VE3OY.COM - MAY 2018                      */
/* GPL licensing - Free to use as long as you leave THIS info */
/* ---------------------------------------------------------- */
//error_reporting(E_ALL);

function FormatRecord( $data ){
	//if(is_array($data)) { echo "Array with " . count($data) . " elements<br>"; }
	//
	// Reverse sort - example: type, ttl, target, serial, rname, retry, refresh, pri, mname, minimum-ttl, expire, class, etc.
	krsort($data);
	//
	foreach($data as $key => $val) {
		echo "<tr colspan='50'>";
		if(substr($key,0,4) == "type") {
			echo "<td><span style='font-size: 1.1rem; font-weight: bold;'>" . strtoupper($key) . "</span></td><td><span style='font-size: 1.1rem; font-weight: bold;'>" . $val . "</span>";
		} else {
			// Display all entries except for "host"
			if(substr($key,0,4) != "host") { echo "<td><span style='font-size: 1rem; font-weight: normal;'>" . strtoupper($key) . "</span></td><td><span style='font-size: 1rem; font-weight: normal;'>" . $val . "</span></td>"; }
		}
		echo "</tr>";
	}
}

$result = "";
$domain = "";
if(isset($_GET["domain"])) {
	//
	$domain = htmlentities($_GET["domain"], ENT_QUOTES | ENT_IGNORE, "UTF-8");
	$result = dns_get_record("$domain");
	// Sort record types - example: A, AAAA, MX, NS, SOA, etc.
	sort($result);
	//
	echo "<br>";
	echo "<center><a href='" . $_SERVER['PHP_SELF'] . "'><button>BACK</button></a></center><br>";
	echo "<table border='2'>";
	// Table header
	echo "<tr style='height: 40px;'><td colspan='50'>";
	echo "<span style='color: #0F0; font-size: 1.2rem; font-weight: bold;'>" . strtoupper($domain) . "</span>";
	echo "</td></tr>";
	// Table details
	if($result != "") {
		//
		for($i = 0; $i < count($result); $i++){
			if($i == 0) { echo "<tr style='background: #333;'><td colspan='50'><center>-&nbsp;1&nbsp;-</center></td></tr>"; }
			FormatRecord( $result[$i] );
			if($i != (count($result)-1)) { echo "<tr style='background: #333;'><td colspan='50'><center>-&nbsp;" . ($i+2) . "&nbsp;-</center></td></tr>"; }
		}
	}
	echo "</table>";
} else {
	echo "<div id='container'><br>";
	echo "<center><span style='font-size: 1.3rem; font-weight: bold; text-shadow: 1px 1px #000;'>NSLOOKUP</span><hr style='width: 450px;'><br>Enter a Hostname (example: google.com):<br>";
	echo "<form action='" . $_SERVER['PHP_SELF'] . "'>";
	echo "<input id='domain' type='text' name='domain' size='50' required>&nbsp;&nbsp;<input type='submit'>";
	echo "</form></center><br><br><br>";
	
	echo "<script type='text/javascript'>
		(function() {
		// Set the focus on the input field
		document.getElementById('domain').focus();
	})();
	</script>";
	echo "</div>";
}
?>
</body>
</html>