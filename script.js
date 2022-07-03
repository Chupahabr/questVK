function getGroups(data) {
	const groupsInfo = JSON.parse(data)

	$('#content').html("")

	//Выводим группы на страницу
	groupsInfo.groups.forEach(function (group){
		const element = $(`<div class="group-vk">${group["data-name"]}</div>`)
			.attr("data-id", group["id"])

		element.on("click", function (e){
			const idGroup = $(e.target).attr("data-id");
			$.ajax({
				url: "ajax.php",
				data: {
					method: "audio",
					idGroup: idGroup,
				},
				success: function (data) {
					getAudio(data);
				},
			})
		});

		$('#content').append(element);
	})
}

function getAudio(data) {
	const postsInfo = JSON.parse(data)

	//Создание кнопки Далее с обратокой
	const next = $(`<div id='next'>Далее</div>`)
	next.on("click", function (){
		$.ajax({
			url: "ajax.php",
			data: {
				method: "audio",
				idGroup: postsInfo.idGroup,
				next_from: postsInfo.next_from
			},
			success: function (data) {
				getAudio(data);
			},
		})
	})

	$('#content').html("")
	$('#content').append(next)
	$('#content').append(`<div>Музыка из этих постом ${postsInfo.next_from-100} - ${postsInfo.next_from}</div>`)

	//Выводим музычку на страницу
	if (postsInfo.posts) {
		postsInfo.posts.forEach(function (element) {
			const id = `vk_playlist_${element["owner_id"]}_${element["id"]}`
			const div = $(`<div id="${id}"></div>`)

			VK.Widgets.Playlist(
				id,
				element["owner_id"],
				element["id"],
				element["access_key"]
			)

			$('#content').append(div)
		})
	} else {
		$('#content').append("<div style='color: red;'>Постов с музыкой не найдено</div>")
	}
}

$(document).ready(function () {

	//Отпрвка формы авторизации
	$("#form-vk").submit(function(e) {
		const formVK = $(this);
		$.ajax({
			type: formVK.attr('method'),
			url: formVK.attr('action'),
			data: formVK.serialize(),
			success: function(data) {
				const authInfo = JSON.parse(data)
				if (authInfo.error){
					formVK.append("<div style='color: red'>Ошибка авторизации</div>")
					if(authInfo.error == "need_validation"){
						const windowVk = window.open(authInfo.redirect_uri, "Авторизация", "height=600,width=600")
						window.addEventListener('message', function (data) {
							console.log(data)
						})
					}
				} else {
					formVK.replaceWith("<div style='color: #3c710a'>Вы авторизованы</div>");
				}
			},
		});

		e.preventDefault();
	});

	//Отправка id пользователя
	$("#form-vk-user").submit(function(e) {
		const formVK = $(this);
		$.ajax({
			type: formVK.attr('method'),
			url: formVK.attr('action'),
			data: formVK.serialize(),
			success: function(data) {
				getGroups(data)
			},
		});

		e.preventDefault();
	});
})