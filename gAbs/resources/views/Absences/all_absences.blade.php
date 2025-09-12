@extends('layouts.nav')

@section('content')

<div class="col-xxl">
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Sélectionner un Groupe</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="groupabs">Sélectionner le Groupe:</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="groupabs" aria-label="Default select example">
                            <option selected>Sélectionner le Groupe</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}" data-id="{{$group->id}}" data-groupe="{{$group->groupe}}"data-code_groupe="{{ $group->code_groupe }}" data-num_groupe="{{$group->num_groupe}}" data-annee_formation="{{$group->annee_formation}}">
                                {{ $group->code }} - {{ $group->groupe }} ({{ $group->code_groupe }} - {{ $group->num_groupe }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

               

    <div class="card">
                <h5 class="card-header">Groupes Sélectionnés</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped d-none" id="groupTable">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Nom du Groupe</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                    </tbody>
                  </table>
                </div>
              </div>
             

              <form action="{{ route('add.absences') }}" method="POST">
              @csrf
              <!-- Modal -->
<div class="modal fade" id="stagiaireModal" tabindex="-1" aria-labelledby="stagiaireModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="stagiaireModalLabel">Liste des Stagiaires</h5>

      
        <div class="row ms-5">
                          <label class="col-sm-4 col-form-label" for="basic-default-name">Semaine de Formation :</label>
                          <div class="col-sm-8">
                          <select class="form-select" name="week" id="idWeek" aria-label="Default select example">
                          @foreach ($weeks as $week)
                              <option value="{{ $week['start'] }}" {{ $week['current'] ? 'selected' : '' }} idGroup="0">
                                  {{ $week['label'] }}
                              </option>
                          @endforeach
                        </select>
                          </div>
                        </div>
        
        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      

      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
              <th>Num Inscription</th>
              <th>Nom et Prénom</th>
              <th>Tot.abs</th>
              <th>LUNDI</th>
              <th>MARDI</th>
              <th>MERCREDI</th>
              <th>JEUDI</th>
              <th>VENDREDI</th>
              <th>SAMEDI</th>
              </tr>
            </thead>
            <tbody id="stagiaireTableBody">
              <!-- Rows will be appended here -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Valider Absence</button>
      </div>

    </div>
  </div>
</div>
</form>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){

            $('#groupabs').change(function(){
                let selectedGroup = $("#groupabs option:selected").data();
                if (selectedGroup.id) {
                    let row = `
                        <tr>
                        @if(Auth::user()->can('stagiaire.list'))
                            <td><button type="button" class="btn btn-info" onclick="fetchStagiairesAbs(${selectedGroup.id})">Ajouter absence</button></td>
                        @endif
                            <td>${selectedGroup.num_groupe} - ${selectedGroup.groupe}(${selectedGroup.code_groupe}) - Année : ${selectedGroup.annee_formation}</td>
                        
                        </tr>`;
                    $('#groupTable tbody').html(row);
                    $('#groupTable').removeClass("d-none")
                    $('#groupTable').show();
                }
            });

            $('#idWeek').change(function(){
              const stagiaireModal = document.getElementById('stagiaireModal');
              const modalInstance = bootstrap.Modal.getInstance(stagiaireModal);
              modalInstance.hide();
              fetchStagiairesAbs($("#idWeek option:selected").attr("idGroup"));
              
            });
            
        });

        function fetchStagiairesAbs(groupId) {
          let selectedOption = $("#idWeek option:selected");
          $("#idWeek option").each(function () {
              $(this).attr("idGroup", groupId);
          });
          let selectedWeek = selectedOption.val();
          // You might want to return or use groupId and selectedWeek here
          console.log("Group ID:", groupId, "Selected Week:", selectedWeek);
$.ajax({

    url: `/stagiaires/by-group/${groupId}?selectedWeek=${encodeURIComponent(selectedWeek)}`,

    type: 'GET',

    success: function(data) {
        let tableBody = '';

        if (data.length === 0) {

            tableBody = '<tr><td colspan="3">Aucun stagiaire trouvé pour ce groupe.</td></tr>';

        } else {
          const today = new Date().toISOString().split('T')[0]; // Format 'YYYY-MM-DD'
          data.stagiaires.forEach(function (stagiaire) {
            let abs = stagiaire.total_absences;
let capped = Math.min(abs, 10);
let percent = (capped / 10) * 100;

// Interpolate color between yellow (255,255,0) and red (255,0,0)
let greenValue = 255 - Math.round((capped / 10) * 255);
let color = `rgb(255, ${greenValue}, 0)`; // from yellow to red

tableBody += `<tr>
  <td>${stagiaire.user_id_number}</td>
  <td>${stagiaire.name}</td>
  <td>
    <div class="progress mt-2" style="height: 20px;">
      <div class="progress-bar" 
           style="width: ${percent}%; background-color: ${color};">
        ${abs}
      </div>
    </div>
  </td>`;


  for (let i = 1; i <= 24; i++) {
    if ((i - 1) % 4 === 0) tableBody += `<td>`;

    // Try to find the absence for this seance_number
    const absence = stagiaire.absences.find(a => a.seance_number === i);
    const isChecked = absence !== undefined ? 'checked' : '';

    // Determine the date of the seance if exists
    const seanceDate = absence ? absence.date : null;
    let isDisabled = seanceDate && seanceDate < today ? 'disabled' : '';
    if (data.userPermissions.includes('absence.edit')) {
      isDisabled = '';
    }

    tableBody += `
      <input class="form-check-input" type="checkbox" name="absences[]"
        value="${i}-${stagiaire.id}-${stagiaire.user_id_number}-${data.user_id}-${data.user_id_number}-${data.groupId}"
        id="check-${stagiaire.id}-${i}" ${isChecked} ${isDisabled} />
    `;

    if (i % 4 === 0) tableBody += `</td>`;
  }

  tableBody += `</tr>`;
});
        }

        $('#stagiaireTableBody').html(tableBody);

        new bootstrap.Modal(document.getElementById('stagiaireModal')).show();

    },

    error: function() {

        alert('Erreur lors du chargement des stagiaires.');

    }

});

}
    </script>
@endsection
