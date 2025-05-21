	</section>
</main>
<script type="text/javascript" src="/panel/interface/js/window.event.js"></script>
<script type="text/javascript" src="/share/lib/omsyJS/main.js"></script>
<script type="text/javascript" src="/share/lib/omsyJS/OmsyInterval.js"></script>
<script type="text/javascript" src="/panel/interface/js/OmsyPanel.js"></script>
<script type="text/javascript">
omsyInterval.setRequestInterval('/api/session', 'sessionCounting', (res) => {
	document.getElementById('get-session').textContent = res.total_session+' Session'
})

//window.onload = () => {
	omsyInterval.loadInterval()
//}
</script>
</body>
</html>