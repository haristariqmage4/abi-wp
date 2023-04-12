<style>
	.woocommerce-save-button{
		display: none !important;
	}
	.box14{
		width: 100%;
		margin-top:2px;
		min-height: 310px;
		margin-right: 400px;
		padding:10px;
		z-index:1;
		right:0px;
		float:left;
		background: -webkit-gradient(linear, 0% 20%, 0% 92%, from(#fff), to(#f3f3f3), color-stop(.1,#fff));
		border: 1px solid #ccc;
		-webkit-border-radius: 60px 5px;
		-webkit-box-shadow: 0px 0px 35px rgba(0, 0, 0, 0.1) inset;
	}
	.box14_ribbon{
		position:absolute;
		top:0; right: 0;
		width: 130px;
		height: 40px;
		background: -webkit-gradient(linear, 555% 20%, 0% 92%, from(rgba(0, 0, 0, 0.1)), to(rgba(0, 0, 0, 0.0)), color-stop(.1,rgba(0, 0, 0, 0.2)));
		border-left: 1px dashed rgba(0, 0, 0, 0.1);
		border-right: 1px dashed rgba(0, 0, 0, 0.1);
		-webkit-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.2);
		-webkit-transform: rotate(6deg) skew(0,0) translate(-60%,-5px);
	}
	.box14 h3
	{
		text-align:center;
		margin:2px;
	}
	.box14 p
	{
		text-align:center;
		margin:2px;
		border-width:1px;
		border-style:solid;
		padding:5px;
		border-color: rgb(204, 204, 204);
	}
	.box14 span
	{
		background:#fff;
		padding:5px;
		display:block;
		box-shadow:green 0px 3px inset;
		margin-top:10px;
	}
	.box14 img {
		margin-top: 5px;
		width: 350px;
	}
	.table-box-main {
		box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
		transition: all 0.3s cubic-bezier(.25,.8,.25,1);
	}

	.table-box-main:hover {
		box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
	}
	span ul li{
		margin:4px;
	}
	.marketing_logos{
	width: 300px;
	height: 300px;
	border-radius: 10px;
	}
	.marketing_redirect_links{
		padding: 0px 2px !important;
		background-color: #fcb800;
		height: 52px;
		font-weight: 600 !important;
		font-size: 18px !important;
		min-width: 210px;
	}
	.article-content{
		display: inline-block;
		vertical-align: top;
		background: #f6f6f6;
		border: 0px solid #bdbdbd;
		border-radius: 1px;
		/* padding: 1rem; */
		/* line-height: 1.4; */
		width: 360px;
		margin-right: 1rem;
		margin-bottom: 1rem;
	}
</style>
<script>
	jQuery(document).ready(function () {
		eh_pricing_discount_manage_role('add_user_role');
	});
	function eh_pricing_discount_manage_role(manage_role) {
		jQuery("input[name='save']").hide();
	}
</script>
