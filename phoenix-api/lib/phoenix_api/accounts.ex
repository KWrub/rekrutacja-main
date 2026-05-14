defmodule PhoenixApi.Accounts do
  @moduledoc """
  The Accounts context.
  Provides functions for managing user accounts.
  """

  import Ecto.Query
  alias PhoenixApi.Repo
  alias PhoenixApi.Accounts.User

  @doc """
  Get a user by their API token.
  """
  def get_by_api_token(api_token) do
    User
    |> where([u], u.api_token == ^api_token)
    |> Repo.one()
  end
end
