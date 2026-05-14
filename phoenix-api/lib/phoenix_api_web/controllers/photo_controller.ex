defmodule PhoenixApiWeb.PhotoController do
  use PhoenixApiWeb, :controller

  alias PhoenixApi.Repo
  alias PhoenixApi.Media.Photo
  import Ecto.Query

  plug PhoenixApiWeb.Plugs.Authenticate
  plug PhoenixApiWeb.Plugs.RateLimit when action in [:index]

  def index(conn, _params) do
    current_user = conn.assigns.current_user

    photos =
      Photo
      |> where([p], p.user_id == ^current_user.id)
      |> Repo.all()

    conn
    |> put_status(:ok)
    |> render("photos.json", photos: photos)
  end
end
