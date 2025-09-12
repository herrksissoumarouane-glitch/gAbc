@extends('layouts.nav')

@section('content')

    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Sélectionner un Groupe</h5>
            </div>
            <input
                            type="hidden"
                            class="form-control"
                            value={{$idFormateur}}
                            id="idformateur"/>
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="idComplexe">Sélectionner un Complexe:</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            class="form-control"
                            id="idComplexe"
                            placeholder="Tapez un complexe à rechercher"/>
                        <div id="complexeList" class="list-group position-absolute bg-white"></div>
                    </div>
                </div>

                <div class="row mb-3" id="groupesCheckboxes">
                    <label class="col-sm-2 col-form-label" for="groupes">Sélectionner un Groupe:</label>
                    <div class="col-sm-10">
                    <form method="POST" action="{{ route('Affect.groups') }}" id="groupForm">
                        @csrf
                        <input type="hidden" name="idFormateur" id="idformateur" value="{{ $idFormateur }}"> <!-- pass the user_id -->
                        <input type="hidden" name="filiere_id" id="filiere_id" value="">
                        <div id="groupesList">
                            <!-- Checkboxes will be dynamically inserted here -->
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save Groups</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        // Autocomplete
        $('#idComplexe').keyup(function () {
            let query = $(this).val();
            if (query !== '') {
                $.ajax({
                    url: '/get-complexes/',
                    method: 'GET',
                    data: { query: query },
                    success: function (data) {
                        $('#complexeList').fadeIn().html('');
                        if (data.length > 0) {
                            data.forEach(item => {
                                $('#complexeList').append('<a href="#" class="list-group-item list-group-item-action" data-id="' + item.id + '">' + item.complexe + '</a>');
                            });
                        } else {
                            $('#complexeList').html('<div class="list-group-item">Aucun complexe trouvé</div>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Error fetching data: ", error);
                    }
                });
            } else {
                $('#complexeList').fadeOut();
            }
        });

        // When selecting a complexe
        $(document).on('click', '#complexeList a', function (e) {
            e.preventDefault();
            let complexeName = $(this).text();
            let complexeId = $(this).data('id');
            $('#idComplexe').val(complexeName);
            $('#complexeList').fadeOut();
            let idFormateur = $('#idformateur').val();
            // Get EFPs for selected complexe
            $.ajax({
    url: '/get-efps/' + complexeId + '/' + idFormateur,
    method: 'GET',
    success: function (data) {
    let groupesList = $('#groupesList');
    groupesList.empty();

    // Loop over EFPs
    $.each(data.efps, function (i, efp) {
        let efpBlock = $('<div class="mb-3 border p-2 rounded bg-light"></div>');
        efpBlock.append(`<h5>${efp.efp}</h5>`);

        let filieres = data.filieres[efp.id] || [];
        $.each(filieres, function (j, filiere) {
            let filiereBlock = $('<div class="ms-3 mb-2"></div>');
            filiereBlock.append(`<h6>${filiere.filiere}</h6>`);

            let groupes = data.groupes[filiere.id] || [];
            $.each(groupes, function (k, groupe) {
                let isChecked = data.assignedGroupIds.includes(groupe.id) ? 'checked' : '';
                filiereBlock.append(`
                    <div class="form-check ms-4">
                        <input class="form-check-input" type="checkbox" name="groupes[]" value="${groupe.id}" id="groupe${groupe.id}" ${isChecked}>
                        <label class="form-check-label" for="groupe${groupe.id}">
                            ${groupe.groupe} (${groupe.code_groupe}) - ${groupe.annee_formation}
                        </label>
                    </div>
                `);
            });

            efpBlock.append(filiereBlock);
        });

        groupesList.append(efpBlock);
    });

    // Optionally set first filiere_id
    let firstFiliereId = Object.values(data.filieres)[0]?.[0]?.id || '';
    $('#filiere_id').val(firstFiliereId);
},
    error: function (xhr, status, error) {
        console.log("Error fetching EFPs: ", error);
    }
});

        });



    });
</script>

@endsection
