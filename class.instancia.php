<?php

class Instancia{
	var $CiaID;
	var $TriID;
	var $LecID;
	var $NivID;
	var $Nombre;
	var $Siglas;
	var $Orden;
	var $Opcional;
	var $Promedio;
	var $Trimestre;
	var $Desde;
	var $Hasta;
	
	/*function Instancia(){
		$this->CiaID=0;
		$this->TriID=0;
		$this->LecID=0;
		$this->NivID=0;
		$this->Nombre='';
		$this->Orden=0;
		$this->Opcional=0;
		$this->Promedio=1;
		$this->Trimestre='';
		$this->Desde='';
		$this->Hasta='';
	}//*/
	function Instancia($c, $t, $l, $n, $nn, $s, $o, $oo, $p, $tt, $d, $h){
		$this->CiaID=$c;
		$this->TriID=$t;
		$this->LecID=$l;
		$this->NivID=$n;
		$this->Nombre=$nn;
		$this->Siglas=$s;
		$this->Orden=$o;
		$this->Opcional=$oo;
		$this->Promedio=$p;
		$this->Trimestre=$tt;
		$this->Desde=$d;
		$this->Hasta=$h;
	}//*/
	

}//fin class


?>