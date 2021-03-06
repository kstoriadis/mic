<?php
class Dialogo_model extends My_Model
{

    public $before_create = array('created_at', 'updated_at');
    public $before_update = array('updated_at');
    public $protected_attributes = array('id');
    protected $soft_delete = TRUE;
/*
 *
 * $query = "select concat_ws(' ', primer_nombre, ultimo_nombre) as nombre, da.id as alumno_id,
                        min(dr.created_at) as fecha, count(distinct dr.id) as cant,
                        sum(if(calificacion = 'rojo',1,0)) as rojo,
                        sum(if(calificacion = 'amarillo',1,0)) as amarillo,
                        sum(if(calificacion = 'verde',1,0)) as verde,
                        sum(if(calificacion is null and dr.id is not null,1,0)) as pendiente,
                        max(calificacion_cuando) as fecha,
                        da.status
                  from dialogo_alumnos da
                  left join dialogo_respuestas dr on dr.dialogo_alumno_id = da.id
                  where da.dialogo_id = $id_dialogo
                  group by da.id
                  order by nombre";
        return $this->db->query($query)->result();*/

    function obtenerTodosLosPrismas() {
        $query = "SELECT p.id, p.nombre, p.descripcion, u.name as autor, p.fecha, p.profesional, p.secundario, p.dialogos, p.publico
                  FROM prisma p join users u on u.id = p.creador ORDER BY p.id DESC ";
        return $this->db->query($query)->result();
    }

    function obtenerTodosLosPrismasPorUsuario($id) {
        $query = "SELECT p.id, p.nombre, p.descripcion, u.name as autor, p.fecha, p.profesional, p.secundario, p.dialogos, p.publico
                  FROM prisma p join users u on u.id = p.creador  where p.creador = '$id' ORDER BY p.id DESC ";

        return $this->db->query($query)->result();
    }

    function obtenerPrisma($id) {
        $query = "select p.id, p.nombre, p.descripcion,p.creador, p.fecha, p.profesional, p.secundario, p.dialogos, p.publico
                  from prisma p
                  where p.id = $id";
        return $this->db->query($query)->row();
    }

    function obtenerDialogosPorPrisma($id) {
        $query = "select *
                  from dialogo d
                  where d.prisma = $id";
        return $this->db->query($query)->result();
    }

    function obtenerDialogosPorPrismaCalificables($id, $alias) {
        $query = "select *
                  from dialogo d
                  where d.prisma = $id AND d.terminado = 1 AND d.evaluado != '$alias' AND d.secundario != '$alias'";
        return $this->db->query($query)->result();
    }
    function obtenerDialogosPorPrismaTerminados($id) {
        $query = "select *
                  from dialogo d
                  where d.prisma = $id AND d.terminado = 1 ";
        return $this->db->query($query)->result();
    }
    function obtenerDialogosPorPrismaCalificados($id, $alias) {
        $query = "select d.id
                  from dialogo d JOIN evaluacion e on d.id = e.dialogo
                  where d.prisma = $id AND e.alias = '$alias'";
        return $this->db->query($query)->result();
    }

    function obtenerDialogosPorId($id) {
        $query = "select *
                  from dialogo d
                  where d.id = $id";
        return $this->db->query($query)->row();
    }

    function obtenerDialogosPorPrismaConPromedioEvaluacion($id) {
        $query = "SELECT d.*, AVG(e.puntaje) as promedio
                  FROM dialogo d JOIN evaluacion e on d.id = e.dialogo
                  WHERE d.primsa = $id group by e.dialogo";
        return $this->db->query($query)->result();
    }

    function obtenerIntervencionesPorDialogo($id) {
        $query = "select *
                  from intervencion i
                  where i.dialogo = $id order by i.id ASC ";
        return $this->db->query($query)->result();
    }

    function obtenerEvaluacionesPorDialogo($id) {
        $query = "select *
                  from evaluacion e
                  where e.dialogo = $id";
        return $this->db->query($query)->result();
    }

    function obtenerMiEvaluacion($id,$alias) {
        $query = "select *
                  from evaluacion e
                  where e.dialogo = $id and e.alias = '$alias'";
        return $this->db->query($query)->result();
    }

    function obtenerMisEvaluacion($alias) {
        $query = "select *
                  from evaluacion e
                  where e.alias = $alias";
        return $this->db->query($query)->result();
    }

    function obtenerDetalleDialogoPorPrismaConAlias($id, $alias) {

    }

    function obtenerPromedioEvaluacionesPorDialogo($id) {

    }

    function crearPrisma($nombre, $descripcion, $creador, $profesional, $secundario){
        $query = "INSERT INTO enconstr_mic.prisma (id, nombre, descripcion, creador, fecha, profesional, secundario)
      VALUES (NULL, '$nombre', '$descripcion', '$creador', CURRENT_TIMESTAMP, '$profesional', '$secundario')";
        $this->db->query($query);
        return $this->db->insert_id();
    }

    function editarPrisma($id,$nombre, $descripcion, $profesional, $secundario){
        $query = "UPDATE prisma SET nombre = '$nombre', descripcion = '$descripcion',profesional = '$profesional',
                  secundario = '$secundario' WHERE prisma.id = '$id'";

        $this->db->query($query);

    }
    function borrarPrisma($id){
        $query = " DELETE FROM enconstr_mic.prisma WHERE prisma.id = '$id'";
        $this->db->query($query);
    }


    function publicar($id,$publico){
        $query = "UPDATE prisma SET publico = $publico WHERE prisma.id = $id";

        $this->db->query($query);

    }

    function cantidadDialogos($idPrisma)
    {
        //obtengo dialogos con count
        $query = "select count(*) as cant
                  from dialogo d
                  where d.prisma = $idPrisma";
        return $this->db->query($query)->row();
    }

        function editarCantidadDialogos($idPrisma){
        //obtengo dialogos con count
        $query = "select count(*) as cant
                  from dialogo d
                  where d.prisma = $idPrisma";
        $result = $this->db->query($query)->result();

        if($result[0]){
            //print "entro" . $result[0]->cant;
            $cantidad = intval($result[0]->cant);
            $query = "UPDATE prisma SET dialogos = $cantidad where  prisma.id = $idPrisma  ";

            $this->db->query($query);
           // print "ejecuto";
        }
    }

    function crearDialogos($idPrisma, $n, $primer){

       for($i=0;$i<$n; $i++){
           $query = "INSERT INTO dialogo (id, prisma, evaluado, secundario, evaluacion,etiqueta) VALUES (NULL, '$idPrisma', '', '', '0',$primer)";
           $this->db->query($query);
           $primer++;
       }
        $this->editarCantidadDialogos($idPrisma);
    }

    function tomarRol($idDialogo, $alias, $profesional){
        //$profesional boolean

        if($profesional){
            $query = "UPDATE dialogo SET evaluado = '$alias' WHERE dialogo.id = $idDialogo";
            $this->insertarIntervencion($idDialogo, $alias, $alias . ' se incorporó al diálogo' ,1,3);
        }else{
            $query = "UPDATE dialogo SET secundario = '$alias' WHERE dialogo.id = $idDialogo";
            $this->insertarIntervencion($idDialogo, $alias, $alias . ' se incorporó al diálogo' ,0,3);
        }
        $this->db->query($query);
    }

    function insertarIntervencion($dialogo, $alias, $texto, $profesional, $tipo){

        $query = "INSERT INTO intervencion (dialogo, profesional, texto, fecha, tipo) VALUES ( '$dialogo', '$profesional', '$texto', CURRENT_TIMESTAMP,$tipo)";
        $this->db->query($query);
    }

    function crearEvaluacionDocente($dialogo, $creador, $puntaje,$sugerencias, $valoracionPositiva, $aclaraciones){
        $query = "UPDATE dialogo SET evaluacion = $puntaje, sugerencia = '$sugerencias', positivo = '$valoracionPositiva', aclaracion = '$aclaraciones' WHERE dialogo.id = $dialogo";
        $this->db->query($query);
    }

    function obtenerEvaluacionDocente($dialogo){
        $query = "select *
                  from dialogo d
                  where d.id = $dialogo and d.evaluacion != 0";
        return $this->db->query($query)->result();
    }
    
    function insertarEvaluacionPar($dialogo, $alias, $puntaje, $sugerencias, $valoracionPositiva, $aclaraciones){
        $query = "INSERT INTO evaluacion (id, dialogo, alias, puntaje, sugerencia, positivo, aclaracion) VALUES (NULL, '$dialogo', '$alias', '$puntaje', '$sugerencias', '$valoracionPositiva', '$aclaraciones')";
        $this->db->query($query);
    }

    function levantarse($dialogo,$alias, $profesional){
        if($profesional){

            $query = "UPDATE dialogo SET evaluado = '' WHERE dialogo.id = $dialogo";
            $this->insertarIntervencion($dialogo, $alias, $alias . ' abandono el diálogo' ,1,3);
        }else{
            $query = "UPDATE dialogo SET secundario = '' WHERE dialogo.id = $dialogo";
            $this->insertarIntervencion($dialogo, $alias, $alias . ' abandono diálogo' ,0,3);
        }
        $this->db->query($query);
    }

    function terminarConversacion($dialogo){
        $query = "UPDATE dialogo SET terminado = 1 WHERE dialogo.id = $dialogo";
        $this->db->query($query);
    }


    function duplicar($id) {
        $query = "INSERT INTO enconstr_mic.prisma (nombre, descripcion, creador, fecha, profesional, secundario, dialogos, publico)
      select  concat('Copia de ', p.nombre), p.descripcion,p.creador, now(), p.profesional, p.secundario, p.dialogos, p.publico
                  from prisma p
                  where p.id = $id";
        $this->db->query($query);
        return $this->db->insert_id();

    }

    function obtenerDialogoPendiente($id, $alias) {
        $query = "select d.id
                  from dialogo d
                  where d.prisma = $id AND d.terminado =0 AND (d.evaluado = '$alias' OR d.secundario = '$alias')";
        return $this->db->query($query)->row();
    }

    function obtenerPrimerDialogoSinRolPorPrisma($id, $profesional){
        $query = "select  d.id
                  from dialogo d
                  where d.prisma = $id  AND ";
        if($profesional){
            $query .= "d.evaluado = ''";
        }else{
            $query .= "d.secundario = ''";
        }
        $query .= ' LIMIT 1';
        return $this->db->query($query)->row();
    }

    function obtenerNuevasIntervenciones($dialogoId,$intervencionId){
        $query = "select *
                  from intervencion i
                  where i.dialogo = $dialogoId and i.id > $intervencionId order by i.id ASC ";
            return $this->db->query($query)->result();

    }

}