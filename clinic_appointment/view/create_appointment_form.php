<form method="POST" action="create_appointment.php">
    <label for="appointment_date">Appointment Date:</label>
    <input type="datetime-local" id="appointment_date" name="appointment_date" required>
    <br>
    <label for="reason">Reason for Appointment:</label>
    <input type="text" id="reason" name="reason" required>
    <br>
    <button type="submit">Create Appointment</button>
</form>
