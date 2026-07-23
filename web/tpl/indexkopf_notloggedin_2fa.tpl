		<h3>Anmeldung - 2 Faktor</h3>
		<form method="post" action="index.php" id="otpform">
			<input type="hidden" name="loginmodule_2fa" value="1" />
			<table class="liste">
				<tr>
					<th>OTP Token:</th>
					<td>
						<input type="text" id="otp" name="otp" minlength="6" maxlength="6" autocomplete="one-time-code" required />
					</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td style="text-align: right">
						<button type="submit">Absenden</button>
					</td>
				</tr>
			</table>
		</form>
		<br/>
		<!--Statusmeldung-->
		<br/>
		<script>
			const input = document.getElementById('otp');
			const form = document.getElementById('otpform');

			input.addEventListener('input', function () {
				if (this.value.length === 6) {
					form.submit();
				}
			});
		</script>