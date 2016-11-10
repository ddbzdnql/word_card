	function generateRequest(){
				var toRet;
				try{
					toRet = new XMLHttpRequest();
				}
				catch(e1){
					try{
						toRet = new ActiveXObject("Msxm12.XMLHTTP");
					}
					catch(e2){
						try{
							toRet = new ActiveXObject("Microsoft.XMLHTTP");
						}
						catch(e3){
							toRet = false;
						}
					}
				}
				return toRet;
			}

