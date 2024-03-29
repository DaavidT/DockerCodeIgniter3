<?php
class DashboardDB extends CI_model{
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function registroArchivoPre($tamano, $fecha, $tipo){
        return $this->db->insert('archivo_cargado',['tamano'=>$tamano, 'fecha'=>$fecha, 'tipo'=>$tipo]);
    }
    
    public function registroArchivo($nombre, $ruta, $tamano, $fecha, $tipo, $cat, $categoria, $id_carpeta){
        return $this->db->query("INSERT INTO archivo_cargado VALUES (NULL, '$nombre', '$ruta', '$tamano', '$fecha', '$tipo', '$cat', '$categoria', $id_carpeta);");
    }

    public function registrarCarpeta($nombre, $path, $id_categoria){
        return $this->db->query("INSERT INTO carpeta VALUES (NULL,'$nombre','$path',$id_categoria);");
    }

    public function eliminarArchivo($ruta){
        return $this->db->query("DELETE FROM archivo_cargado WHERE ruta = '$ruta';");
    }

    public function modificarArchivo($id,$nombreEditado){
        return $this->db->query("UPDATE archivo_cargado SET nombre = '$nombreEditado' WHERE (id = '$id');");
    }

    public function devolverArchivos(){
        $consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado ORDER BY id DESC;");
        return $consultaArchivos->result_id;
    }

    public function devolverArchivosLimitCarpeta($ruta, $inicio, $limite){
        $consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado WHERE ruta LIKE '$ruta%' ORDER BY id DESC LIMIT $inicio,$limite");
        return $consultaArchivos->result_id;
    }

    public function devolverArchivosLimitRaiz($inicio, $limite){
        $consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado WHERE ruta NOT LIKE 'cargados/%/%' ORDER BY id DESC LIMIT $inicio,$limite");
        return $consultaArchivos->result_id;
    }

    public function ultimoIdArchivos(){
        $consultaArchivos = $this->db->query("SELECT MAX(id) indice FROM archivo_cargado;");
        return $consultaArchivos->result();
    }

    public function devolverArchivosPre(){
        $consultaArchivos = $this->db->get('archivo_cargado');
        return $consultaArchivos;
    }

    public function devolverArchivosTipoLimit($id_categoria, $inicio, $limite){
        //$consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado WHERE id_categoria = '$id_categoria' ORDER BY id DESC LIMIT $inicio,$limite;");
        $consultaArchivos = $this->db->query("SELECT a.id id, a.nombre nombre, a.ruta ruta, a.tamano tamano, a.fecha fecha, a.tipo tipo, a.fileType fileType, a.id_categoria categoria, a.id_carpeta id_carpeta FROM archivo_cargado a LEFT JOIN carpeta c ON a.id_carpeta = c.id WHERE a.id_categoria = $id_categoria AND c.id_categoria IS NULL ORDER BY a.id DESC LIMIT $inicio,$limite;");
        return $consultaArchivos->result_id;
    }

    public function devolverArchivosTipo($id_categoria){
        $consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado WHERE id_categoria = '$id_categoria' ORDER BY id DESC;");
        return $consultaArchivos->result_id;
    }

    public function devolverArchivosFiltro($inicio, $limite, $tamanoMax, $orden){
        $resultado = $this->db->query("SELECT * FROM archivo_cargado WHERE ruta NOT LIKE 'cargados/%/%' AND ((tamano LIKE '%GB' AND REPLACE(tamano,' GB','') < $tamanoMax/1000) OR (tamano LIKE '%MB' AND REPLACE(tamano,' MB','') < $tamanoMax) OR (tamano LIKE '%KB' AND REPLACE(tamano,' KB','') < $tamanoMax*1000)) ORDER BY id $orden LIMIT $inicio,$limite");
        return $resultado->result_id;
    }

    public function devolverArchivo($id){
        $consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado WHERE id = '$id';");
        return $consultaArchivos->result();
    }

    public function devolverCarpeta($id){
        $consultaArchivos = $this->db->query("SELECT * FROM carpeta WHERE id = '$id';");
        return $consultaArchivos->result();
    }

    public function eliminarArchivosDeCarpeta($ruta){
        $this->db->query("DELETE FROM archivo_cargado WHERE ruta LIKE '$ruta%';");
        $this->db->query("DELETE FROM carpeta WHERE ruta LIKE '$ruta%';");
    }

    public function idCarpetaPath($path){
        $consultaFolder = $this->db->query("SELECT * FROM carpeta WHERE ruta = '$path';");
        $res = $consultaFolder->result();
        return $res[0]->id;
    }

    public function infoFoldersRuta($ruta){
        $consultaArchivos = $this->db->query("SELECT * FROM carpeta WHERE ruta = '$ruta' AND id_categoria IS NULL;");
        return $consultaArchivos->result_id;
    }

    public function infoFoldersRutaCat($ruta, $id_cat){
        $consultaArchivos = $this->db->query("SELECT * FROM carpeta WHERE ruta = '$ruta' AND id_categoria = '$id_cat';");
        return $consultaArchivos->result_id;
    }

    public function infoFoldersId($id){
        $consultaArchivos = $this->db->query("SELECT * FROM carpeta WHERE id = '$id';");
        return $consultaArchivos->result();
    }
    
    public function ultimaCategoria(){
        $consultaArchivos = $this->db->query("SELECT c.descripcion descripcion, c.id id_cat FROM archivo_cargado a INNER JOIN categoria c ON a.id_categoria = c.id WHERE a.id = (SELECT MAX(id) FROM archivo_cargado);");
        $consultaArchivos = $consultaArchivos->result();
        return $consultaArchivos;
    }

    public function consultaCategorias(){
        $resultado = $this->db->query("SELECT * FROM categoria;");
        return $resultado->result_id;
    }

    public function eliminarPermisosCat($id_cat){
        return $this->db->query("DELETE FROM rol_ve_categoria WHERE id_cat = '$id_cat'");
    }

    public function consultaPermisos(){
        $resultado = $this->db->query("SELECT * FROM rol_ve_categoria;");
        return $resultado->result_id;
    }

    public function actualizarCategoria($id, $descripcion){
        return $this->db->query("UPDATE categoria SET descripcion = '$descripcion' WHERE id = '$id';");
    }

    public function registrarCategoria($descripcion){
        $this->db->query("INSERT INTO categoria VALUES(NULL,'$descripcion');");
        $this->db->query("INSERT INTO rol_ve_categoria VALUES(1,(SELECT MAX(id) FROM categoria));");
    }

    public function archPorcat($idcat){
        $resultado = $this->db->query("SELECT * FROM categoria INNER JOIN archivo_cargado ON categoria.id = archivo_cargado.id_categoria WHERE categoria.id = '$idcat';");
        return $resultado;
    }

    public function eliminarCategoria($id){
        return $this->db->query("DELETE FROM categoria WHERE id = '$id'");
    }

    public function usuariosCorreo($id_cat){
        $correos = $this->db->query("SELECT u.correo FROM usuario u INNER JOIN rol r ON u.id_rol = r.id INNER JOIN rol_ve_categoria rvc ON r.id = rvc.id_rol WHERE rvc.id_cat = '$id_cat'");
        return $correos->result();
    }

    public function buscarArchivosLimit($key, $inicio, $limite){
        $resultado = $this->db->query("SELECT * FROM archivo_cargado WHERE nombre LIKE LOWER('%$key%') LIMIT $inicio,$limite;");
        return $resultado->result_id;
    }

    public function buscarArchivos($key){
        $resultado = $this->db->query("SELECT * FROM archivo_cargado WHERE nombre LIKE LOWER('%$key%');");
        return $resultado->result_id;
    }

    public function buscarCarpetas($key){
        $resultado = $this->db->query("SELECT * FROM carpeta WHERE nombre LIKE LOWER('%$key%');");
        return $resultado->result_id;
    }

    public function tamanoMaximo($unidad, $path){
        $resultado = $this->db->query("SELECT MAX(tamano) maximo FROM archivo_cargado WHERE tamano LIKE '%$unidad' AND ruta NOT LIKE '$path%/%'");
        return $resultado->result();
    }

    public function maxIdCrmFiles(){
        $resultado = $this->db->query("SELECT MAX(id) maximo FROM archivo_cargado WHERE ruta LIKE 'cargados/CRM_Files/%'");
        $resultado = $resultado->result();
        return $resultado[0]->maximo;
    }
}
?>