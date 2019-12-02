<table id="contatos_table">
	<thead>
		<tr>
			<th>
				Data
			</th>
			<th>
				Título
			</th>
			<th>
				Estado
			</th>
		</tr>
	</thead>
	<tbody>

		<?php
		if($history) {
			foreach($history as $contato) {

			include 'timewarning.php';
				echo "<td>" . $contato['agendado'] . "</td>";
				echo "<td>" . $contato['titulo'] . "</td>";
				//echo "<td>" . $contato['description'] . "</td>";
				echo "<td>";
				echo '<div class="form-check form-check-inline">
											<div class="pretty p-default p-round">
											<input class="completed_check" type="checkbox" data-id="' . $contato['id'] . '"  ';
				if(strlen($contato['completado'])>1) {
					echo " checked";
				}
				echo '>';
				echo '
									<div class="state p-success-o">
										<label>' . $time_alert . '</label>
									</div>
								</div>';
				echo '</div>';
				echo "</td>";
				echo "</tr>";
			}
		}
		?>


	</tbody>
</table>
