<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../asstes/bootstrap/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      // Gérer la navigation en fonction des clics sur les liens du menu
      $('.nav-link').click(function() {
        var target = $(this).data('target');
        $('.content').hide();
        $('#' + target).show();
      });

      // Gérer la déconnexion
      $('#btn-logout').click(function() {
        $('#logoutModal').modal('show');
      });

      // Remplir le formulaire de modification d'étudiant avec les données actuelles
      $('.edit-student').click(function() {
        var studentId = $(this).data('student-id');
        var studentName = $(this).closest('tr').find('td:eq(1)').text();
        var studentEmail = $(this).closest('tr').find('td:eq(4)').text();
        var studentPhone = $(this).closest('tr').find('td:eq(3)').text();
        var studentCourse = $(this).closest('tr').find('td:eq(6)').text();
        var studentStartDate = $(this).closest('tr').find('td:eq(7)').text();

        $('#edit_student_id').val(studentId);
        $('#edit_student_name').val(studentName);
        $('#edit_student_email').val(studentEmail);
        $('#edit_student_phone').val(studentPhone);
        $('#edit_student_course').val(studentCourse);
        $('#edit_student_start_date').val(studentStartDate);
      });
    });
  </script>
</body>

</html>