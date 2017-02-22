<?php
    interface Contabilidad_Interfaces_IPagoProveedor{
    	public function obtieneFacturaProveedor($idSucursal, $idCoP, $numeroFactura);
		public function obtenerFactura(); 		
    
    }
?>