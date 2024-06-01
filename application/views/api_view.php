<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div id="data"></div>

<script type="text/javascript>" language="javascript">
    $(document).ready(function() {
        function fetch_data() {
            $.ajax({
                url: "<php echo base_url(); ?>test_api/action",
                method: "POST",
                data: {data_action: "fetch_all"},
                success:function(data) {
                    $('#data').html(data);
                }
            });
        }

        fetch_data();
    });
</script>
