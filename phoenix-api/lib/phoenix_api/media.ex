defmodule PhoenixApi.Media do
  @moduledoc """
  The Media context.
  Provides functions for managing photos.
  """

  import Ecto.Query
  alias PhoenixApi.Repo
  alias PhoenixApi.Media.Photo

  @doc """
  Get all photos for a specific user.
  """
  def get_user_photos(user_id) do
    Photo
    |> where([p], p.user_id == ^user_id)
    |> Repo.all()
  end
end
