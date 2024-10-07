<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="satisfaccionModal" hidden>
  Launch demo modal
</button>

<style>
    i:hover {
  color: blue;
  cursor: pointer;
}

</style>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Encuesta de Satisfaccion</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <hr>
      <div class="modal-body">
        <h4> Grado de Satisfacion :<h4> <br>
        <i class="fas fa-smile-beam fa-4x fa-lg"></i> <i class="fas fa-meh fa-4x fa-lg"></i>  <i class="fas fa-frown fa-4x fa-lg"></i>
      </div>
      <hr>
      <div class="modal-footer">
      
        <button type="button" class="btn btn-primary">Enviar Encuesta</button>
      </div>
    </div>
  </div>
</div>