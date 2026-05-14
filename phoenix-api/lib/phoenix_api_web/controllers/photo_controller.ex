defmodule PhoenixApiWeb.PhotoController do
  use PhoenixApiWeb, :controller

  alias PhoenixApi.Media

  plug PhoenixApiWeb.Plugs.Authenticate
  plug PhoenixApiWeb.Plugs.RateLimit when action in [:index]

  def index(conn, _params) do
    current_user = conn.assigns.current_user
    photos = Media.get_user_photos(current_user.id)

    conn
    |> put_status(:ok)
    |> render("photos.json", photos: photos)
  end
end
