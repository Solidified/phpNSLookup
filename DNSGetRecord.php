<html>
<head>
<title>phpNSLookup</title>
<!-- ---------------------------------------------------------------- -->
<!-- Written by VE3OY@VE3OY.COM / matt.ironstone@gmail.com - MAY 2018 -->
<!-- GPL licensing - Free to use as long as you leave THIS info       -->
<!-- ---------------------------------------------------------------- -->
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
/* ---------------------------------------------------------------- */
/* Written by VE3OY@VE3OY.COM / matt.ironstone@gmail.com - MAY 2018 */
/* GPL licensing - Free to use as long as you leave THIS info       */
/* ---------------------------------------------------------------- */
// No error reporting
//error_reporting(0);
//
// Error reporting ON
error_reporting(E_ALL);
/* ---------------------------------------------------------------- */
function FormatRecord( $data ){
	// Display how many elements have been returned by the lookup
	//if(is_array($data)) { echo "Array with " . count($data) . " elements<br>"; }
	//
	// Reverse sort - example: type, ttl, target, serial, rname, retry, refresh, pri, mname, minimum-ttl, expire, class, etc.
	krsort($data);
	//
	foreach($data as $key => $val) {
		echo "<tr colspan='50'>";
		//echo "<tr style='background: #333;'><td colspan='50'><center>&nbsp;" . count($val) . "</center></td></tr>";
		
		// if returned entry is an ARRAY
		if(is_array($val)) {
			// Any information in an ARRAY is usually a repeat of a previous field - so IGNORE it
			/*
			foreach($val as $key2 => $val2) {
				$tmpval = implode(" ",$val);
				echo "<td><span style='font-size: 1rem; color: #FF0; font-weight: normal;'>Array($key2)</span></td><td><span style='font-size: 1rem; font-weight: normal;'>" . $val2 . "</span></td>";
				//if(substr($key,0,4) != "host") { echo "<td><span style='font-size: 1rem; font-weight: normal;'>" . strtoupper($key2) . "</span></td><td><span style='font-size: 1rem; color: #0F0; font-weight: normal;'>[Array]</span><span style='font-size: 1rem; font-weight: normal;'>&nbsp;" . $tmpval . "</span></td>"; }
			}
			*/
		} else {
			// returned entry is NOT an ARRAY
			if(substr($key,0,4) == "type") {
				echo "<td><span style='font-size: 1.1rem; font-weight: bold;'>" . strtoupper($key) . "</span></td><td><span style='font-size: 1.1rem; font-weight: bold;'>" . $val . "</span>";
			} else {
				// Display all entries except for "host"
				if(substr($key,0,4) != "host") { echo "<td><span style='font-size: 1rem; font-weight: normal;'>" . strtoupper($key) . "</span></td><td><span style='font-size: 1rem; font-weight: normal;'>" . $val . "</span></td>"; }
			}
		}
		
		echo "</tr>";
	}
}
/* ---------------------------------------------------------------- */
// Define our variables
$result = "";
$domain = "";
$key = "";
$key2 = "";
$val = "";
$val2 = "";
//
if(isset($_GET["domain"])) {
	//
	$domain = htmlentities($_GET["domain"], ENT_QUOTES | ENT_IGNORE, "UTF-8");
	$result = dns_get_record("$domain");
	//
	if($result != "") {
		// Sort record types - example: A, AAAA, MX, NS, SOA, etc.
		sort($result);
		echo "<center>";
		echo "<span style='color: #0F0; font-size: 1.2rem; font-variant: small-caps;'>The DNS record returned " . count($result) . " entries</span><br>";
		echo "</center><br>";
		// Table start
		echo "<table border='2'>";
		// Table header
		echo "<tr style='height: 40px;'><td colspan='50'>";
		echo "<span style='color: #0F0; font-size: 1.2rem; font-weight: bold;'>" . strtoupper($domain) . "</span>";
		echo "</td></tr>";
		//
		// Table details
		for($i = 0; $i < count($result); $i++){
			if($i == 0) { echo "<tr style='background: #333;'><td colspan='50'><center>-&nbsp;1&nbsp;-</center></td></tr>"; }
			FormatRecord( $result[$i] );
			if($i != (count($result)-1)) { echo "<tr style='background: #333;'><td colspan='50'><center>-&nbsp;" . ($i+2) . "&nbsp;-</center></td></tr>"; }
		}
		echo "<tr style='background: #333;'><td colspan='50'><center>&nbsp;</center></td></tr>";
		echo "<tr><td colspan='50'style='color: #0F0; font-size: 1.2rem;'><center>IP Address:&nbsp;&nbsp;&nbsp;" . gethostbyname($domain) . "</center></td></tr>";
		// Table end
		echo "<tr style='background: #333;'><td colspan='50'><center>&nbsp;</center></td></tr>";
		echo "<tr><td colspan='50'style='color: #0F0; font-size: 1.2rem;'><br><center><a href='" . $_SERVER['PHP_SELF'] . "'><button>BACK</button></a></center><br></td></tr>";
		echo "</table><br>";
	}
} else {
	// No domain was given to search - display search input dialog
	echo "<div id='container'><br>";
	echo "<center><span style='font-size: 1.3rem; font-weight: bold; text-shadow: 1px 1px #000;'>NSLOOKUP</span><hr style='width: 450px;'><br>Enter a Hostname (example: google.com):<br>";
	echo "<form action='" . $_SERVER['PHP_SELF'] . "'>";
	echo "<input id='domain' type='text' name='domain' size='50' required>&nbsp;&nbsp;<input type='submit'>";
	echo "</form></center><br><br><br>";
	//
	echo "<script type='text/javascript'>
		(function() {
		// Set the focus on the input field
		document.getElementById('domain').focus();
	})();
	</script>";
	echo "</div>";
}
?>
<br><br><br><br><br>
</body>
</html>
