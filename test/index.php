<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Table User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="p-3">

     <div class="row mb-3 mt-3">
      <div class="col">
        <h1>Table Catatan Hadiah</h1>
      </div>
      <div class="col text-end">
        <!-- Tombol Add -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add</button>
      </div>
    </div>

    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Hadiah</th>
          <th>Nama Pemberi</th>
          <th>Tanggal Pemberi</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="catatanHadiahTable"></tbody>
    </table>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="addForm">
            <div class="modal-header">
              <h5 class="modal-title" id="addLabel">Tambah Catatan Hadiah</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Nama Hadiah</label>
                <input type="text" class="form-control" name="nama_hadiah" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Nama Pemberi</label>
                <input type="text" class="form-control" name="nama_pemberi" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Tanggal Pemberi</label>
                <input type="date" class="form-control" name="tanggal_terima" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success" >Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      function renderTable() {
        fetch('http://localhost:8000/api/user')
          .then(response => response.json())
          .then(data => {
            let catatanHadiahTable = document.getElementById('catatanHadiahTable');
            let element = '';

            if (data.data.length === 0) {
              element = `
                <tr>
                  <td colspan="5" class="text-center">No Data Found</td>
                </tr>
              `;
            }

            data.data.forEach((item, key) => {
              element += `
                <tr>
                  <td>${key + 1}</td>
                  <td>${item.nama_hadiah}</td>
                  <td>${item.nama_pemberi}</td>
                  <td>${item.tanggal_terima}</td>
                  <td>
                    <!-- Tombol Hapus -->
                    <button type="button" class="btn btn-danger btn-sm" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal${item.id}">
                      Delete
                    </button>

                    <!-- Modal Konfirmasi -->
                    <div class="modal fade" id="deleteModal${item.id}" tabindex="-1" 
                         aria-labelledby="deleteLabel${item.id}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="deleteLabel${item.id}">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Yakin ingin menghapus <b>${item.nama_hadiah}</b> dari <b>${item.nama_pemberi}</b>?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger" 
                                    onclick="deleteUser(${item.id})" data-bs-dismiss="modal">
                              Hapus
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              `;
            });

            catatanHadiahTable.innerHTML = element;
          })
          .catch(error => {
            console.error('Error fetching data:', error);
          });
      }

      renderTable();

      function deleteUser(id) {
        fetch(`http://localhost:8000/api/user/destroy.php?id=${id}`, {
          method: 'DELETE',
        })
        .then(response => response.json())
        .then(result => {
          console.log('User deleted:', result);
          renderTable(); 
        })
        .catch(error => console.error('Error deleting user:', error));
      }

      // Tambah Data
      document.getElementById('addForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let data = Object.fromEntries(formData.entries());

        fetch('http://localhost:8000/api/user/store.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
          console.log('User added:', result);
          renderTable();
          this.reset();
          bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
        })
        .catch(error => console.error('Error adding user:', error));
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
